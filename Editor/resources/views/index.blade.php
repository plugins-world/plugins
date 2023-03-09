@extends('Editor::layouts.master')

@section('content')
<link href="{{ asset('assets/plugins/Editor/editor.md/css/editormd.min.css') }}" rel="stylesheet">

<script src="https://cdn.bootcdn.net/ajax/libs/editor-md/1.5.0/editormd.min.js"></script>
<script src="{{ asset('assets/plugins/Editor/editor.md/editormd.min.js') }}"></script>

<script type="text/javascript">
    $(function() {
        window.editor = editormd("editor", {
            width: "100%",
            height: "100%",
            markdown: "# xxxx", // dynamic set Markdown text
            path: "{{ asset('assets/plugins/Editor/editor.md/lib/').'/' }}" // Autoload modules mode, codemirror, marked... dependents libs path
        });
    });
</script>


<div class="mt-5">
    <div class="container" style="height:500px;">
        <div id="editor">
            <!-- Tips: Editor.md can auto append a `<textarea>` tag -->
            <textarea style="display:none;">### Hello Editor.md !</textarea>
        </div>
    </div>
</div>

<div class="mt-5">
    <div class="container">
        <button onclick="alert(editor.getPreviewedHTML())">获取 HTML</button>
        <button onclick="alert(editor.getMarkdown())">获取 Markdown</button>
        <button onclick="editor.setMarkdown('# 111')">设置 Markdown</button>
    </div>
</div>

<script>
    window.addEventListener('message', function() {
        console.log('some message');
    }, '*');
</script>
@endsection