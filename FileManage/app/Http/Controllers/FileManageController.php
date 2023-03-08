<?php

namespace Plugins\FileManage\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Plugins\FileManage\Models\DiskDirectory;
use Plugins\FileManage\Models\DiskDirectoryFile;
use Plugins\FileManage\Models\File;
use ZhenMu\Support\Traits\ResponseTrait;

class FileManageController extends Controller
{
    use ResponseTrait;

    public function login()
    {
        \request()->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        // $user = User::firstOrCreate([
        //     'name' => \request('username'),
        //     'email' => 'admin@admin.com',
        //     'password' => \Hash::make(\request('password')),
        // ]);

        $loginResult = Auth::attempt([
            'name' => \request('username'),
            'password' => \request('password'),
        ]);

        if (!$loginResult) {
            return $this->fail('登录失败');
        }

        return $this->success();
    }
    
    public function home()
    {
        return view('FileManage::home');
    }

    public function diskDirectories()
    {
        $data = DiskDirectory::paginate();

        return $this->paginate($data);
    }

    public function index()
    {
        \request()->validate([
            'ddid' => 'nullable',
            'type' => 'nullable',
        ]);

        $params = [];
        $params['filename'] = \request('filename');
        $params['ddid'] = \request('ddid');
        $params['type'] = \request('type');

        $files = File::query()
            ->join('disk_directory_files as ddf', 'ddf.fid', '=', 'files.fid')
            ->select('files.*')
            ->when($params['ddid'], function ($query, $value) {
                $query->where('ddid', $value);
            })
            ->when($params['filename'], function ($query, $value) {
                $query->where('alias', 'like', "%$value%");
            })
            ->when($params['type'], function ($query, $value) {
                $query->where('file_type', $value);
            })
            ->paginate();

        if (\request()->wantsJson()) {
            return $this->paginate($files);
        }

        return view('FileManage::index', [
            'files' => $files,
        ]);
    }

    public function transcode()
    {
        \request()->validate([
            'fid' => 'required',
        ]);

        $params = [];
        $params['fid'] = \request('fid');

        $diskDirectoryFile = DiskDirectoryFile::where('fid', $params['fid'])->first();;
        $file = $diskDirectoryFile->file;

        $exitCode = \Artisan::call('ffmpeg:transcode', [
            'file' => $file->realpath,
            '--ddid' => $diskDirectoryFile->ddid,
            '--fid' => $file->fid,
        ]);

        if ($exitCode != 0) {
            return $this->fail('转码失败: '. \Artisan::output());
        }

        return $this->fail('转码成功: '. \Artisan::output());
    }
}
