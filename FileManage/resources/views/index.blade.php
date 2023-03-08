@extends('FileManage::layouts.master')

@section('content')
    <h1 class="h1">文件管理</h1>

    <table class="border-collapse">
        <tr>
            <th>文件名</th>
            <th>真实路径</th>
            <th>文件类型</th>
            <th>文件大小</th>
            <th>操作</th>
        </tr>
        @foreach($files as $file)
        <tr>
            <td>{{ $file['basename'] ?? null }}</td>
            <td>{{ $file['realpath'] ?? null }}</td>
            <td>{{ $file['file_type'] ?? null }}</td>
            <td>{{ $file['size_desc'] ?? null }}</td>
            <td>
                <a href="#">转码</a>
                <a href="{{ $file['url'] }}" target="_blank">预览</a>
            </td>
        </tr>
        @endforeach
    </table>
@endsection
