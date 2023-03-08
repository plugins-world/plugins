<!DOCTYPE html>
<html lang="en" class="h-full bg-white">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>授权认证</title>

    {{-- Laravel Mix - CSS File --}}
    {{-- <link rel="stylesheet" href="{{ mix('css/system-authorization.css') }}"> --}}
    <link rel="stylesheet" href="//rsms.me/inter/inter.css">
    <!-- <link rel="stylesheet" href="//at.alicdn.com/t/c/font_3916539_82v0a9nticr.css"> -->
    <script src="//at.alicdn.com/t/c/font_3916539_82v0a9nticr.js" defer></script>
    <script src="//cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="//buunguyen.github.io/topbar/topbar.min.js" defer></script>
    <script src="//cdn.jsdelivr.net/npm/@sliphua/pjax@2.4.0/dist/pjax.min.js" defer></script>
    <script>
        const pjax = new Pjax({
            elements: 'a[href], form[action]',
            selectors: [
                'title',
                'meta[name=description]',
                'meta[property="og:title"]',
                'meta[property="og:description"]',
                '.sidebar',
                '.content',
            ],
        });
        document.addEventListener('pjax:send', topbar.show);
        document.addEventListener('pjax:complete', topbar.hide);
    </script>
</head>

<body class="h-full overflow-hidden">
    @include('SystemAuthorization::layouts.dashboard')

    {{-- Laravel Mix - JS File --}}
    {{-- <script src="{{ mix('js/system-authorization.js') }}"></script> --}}
</body>

</html>