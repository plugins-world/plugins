<?php

namespace Plugins\PayCenter\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Plugins\LaravelConfig\Models\Config;
use ZhenMu\Support\Traits\ResponseTrait;
use Plugins\MarketManager\Utilities\PluginUtility;

class PayCenterController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        // code
        $configs = [];

        return view('PayCenter::index', [
            'configs' => $configs,
        ]);
    }

    public function showSettingView(Request $request)
    {
        config(['session.same_site' => 'none']);
        config(['session.secure' => uniqid()]);

        $itemKeys = [
            'pay_center_wechatpay',
            'pay_center_alipay',
            'pay_center_unipay',
        ];

        $configs = Config::whereIn('item_key', $itemKeys)->where('item_tag', 'pay_center')->get();

        $wechatPay = $configs->where('item_key', 'pay_center_wechatpay')->first()?->item_value ?? [];
        $aliPay = $configs->where('item_key', 'pay_center_alipay')->first()?->item_value ?? [];
        $uniPay = $configs->where('item_key', 'pay_center_unipay')->first()?->item_value ?? [];

        $version = PluginUtility::fresnsPluginVersionByFskey('PayCenter');

        return view('PayCenter::setting', [
            'wechatPay' => $wechatPay,
            'aliPay' => $aliPay,
            'uniPay' => $uniPay,
            'version' => $version,
        ]);
    }

    public function saveSetting(Request $request)
    {
        $request->validate([
            'wechatPay' => 'nullable|array',
            'aliPay' => 'nullable|array',
            'uniPay' => 'nullable|array',
        ]);

        if ($request->wechatPay) {
            $config = $request->wechatPay;

            $config['mch_secret_cert'] = str_replace(base_path() . '/', '', $config['mch_secret_cert']);
            $config['mch_public_cert_path'] = str_replace(base_path() . '/', '', $config['mch_public_cert_path']);

            Config::updateOrCreate([
                'item_key' => 'pay_center_wechatpay',
            ], [
                'item_value' => $config,
                'item_type' => 'object',
                'item_tag' => 'pay_center',
            ]);
            Config::forgetCache('pay_center_wechatpay');
        }

        if ($request->aliPay) {
            Config::updateOrCreate([
                'item_key' => 'pay_center_alipay',
            ], [
                'item_value' => $request->aliPay,
                'item_type' => 'object',
                'item_tag' => 'pay_center',
            ]);
            Config::forgetCache('pay_center_alipay');
        }

        if ($request->uniPay) {
            Config::updateOrCreate([
                'item_key' => 'pay_center_unipay',
            ], [
                'item_value' => $request->uniPay,
                'item_type' => 'object',
                'item_tag' => 'pay_center',
            ]);
            Config::forgetCache('pay_center_unipay');
        }

        return redirect(route('pay-center.setting'));
    }

    public function uploadFile()
    {
        request()->validate([
            'file' => ['required', 'file'],
            'field' => ['required', 'string'],
        ]);

        $dir = storage_path('app/certs');

        File::ensureDirectoryExists($dir);

        $file = request()->file('file');
        $filename = $file->getClientOriginalName();

        if (!$file->isValid()) {
            return $this->fail('文件无效，上传失败');
        }

        $filepath = $file->storeAs('certs', $filename);

        return $this->success([
            'filepath' => str_replace(base_path() . '/', '', storage_path('app/'.$filepath)),
        ]);
    }

    public function downloadPublicCert()
    {
        $configModel = Config::where('item_key', 'pay_center_wechatpay')->where('item_tag', 'pay_center')->first();

        $config = $configModel->item_value ?? [];

        File::ensureDirectoryExists(storage_path('app/certs'));

        $mch_secret_cert_path = storage_path('app/certs') . '/apiclient_key.pem';
        if (empty($config['mch_secret_cert']) && is_file($mch_secret_cert_path)) {
            $config['mch_secret_cert'] = storage_path('app/certs') . '/apiclient_key.pem';
        }

        $mch_public_cert_path = storage_path('app/certs') . '/apiclient_cert.pem';
        if (empty($config['mch_public_cert_path']) && is_file($mch_public_cert_path)) {
            $config['mch_public_cert_path'] = storage_path('app/certs') . '/apiclient_cert.pem';
        }

        if (!str_starts_with($config['mch_secret_cert'], base_path() . '/')) {
            $config['mch_secret_cert'] = base_path($config['mch_secret_cert']);
        }

        if (!str_starts_with($config['mch_public_cert_path'], base_path() . '/')) {
            $config['mch_public_cert_path'] = base_path($config['mch_public_cert_path']);
        }

        $payConfig = [
            'wechat' => [
                'default' => $config,
            ]
        ];

        \Yansongda\Pay\Pay::config($payConfig);

        $params = [
            '_config' => 'default' // 多租户配置时使用
        ];

        \Yansongda\Pay\get_wechat_public_certs($params, storage_path('app/certs'));
        $files = glob(storage_path('certs/*.crt'));


        $data = [];
        foreach ($files as $file) {
            $serialNo = pathinfo($file, PATHINFO_FILENAME);
            $content = file_get_contents($file);

            $data[$serialNo] = $content;
        }

        $config['wechat_public_cert_path'] = $data;

        $configModel->item_value = $config;
        $configModel->save();

        if (request()->wantsJson()) {
            return $this->success('下载完成');
        }
        
        return back()->with([
            'mnessage' => 'wechat_public_cert_path 下载成功',
        ]);
    }
}
