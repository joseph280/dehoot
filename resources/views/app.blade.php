<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <link rel="icon" type="image/png" href="{{ asset('assets/logo/favicon.png') }}">

  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />

  <link rel="apple-touch-icon" href="{{ asset('pwa/apple-icon-180.png') }}">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <link rel="apple-touch-startup-image" href="{{ asset('pwa/apple-splash-2048-2732.jpg') }}" media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)">
  <link rel="apple-touch-startup-image" href="{{ asset('pwa/apple-splash-2732-2048.jpg') }}" media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)">
  <link rel="apple-touch-startup-image" href="{{ asset('pwa/apple-splash-1668-2388.jpg') }}" media="(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)">
  <link rel="apple-touch-startup-image" href="{{ asset('pwa/apple-splash-2388-1668.jpg') }}" media="(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)">
  <link rel="apple-touch-startup-image" href="{{ asset('pwa/apple-splash-1536-2048.jpg') }}" media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)">
  <link rel="apple-touch-startup-image" href="{{ asset('pwa/apple-splash-2048-1536.jpg') }}" media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)">
  <link rel="apple-touch-startup-image" href="{{ asset('pwa/apple-splash-1668-2224.jpg') }}" media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)">
  <link rel="apple-touch-startup-image" href="{{ asset('pwa/apple-splash-2224-1668.jpg') }}" media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)">
  <link rel="apple-touch-startup-image" href="{{ asset('pwa/apple-splash-1620-2160.jpg') }}" media="(device-width: 810px) and (device-height: 1080px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)">
  <link rel="apple-touch-startup-image" href="{{ asset('pwa/apple-splash-2160-1620.jpg') }}" media="(device-width: 810px) and (device-height: 1080px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)">
  <link rel="apple-touch-startup-image" href="{{ asset('pwa/apple-splash-1290-2796.jpg') }}" media="(device-width: 430px) and (device-height: 932px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)">
  <link rel="apple-touch-startup-image" href="{{ asset('pwa/apple-splash-2796-1290.jpg') }}" media="(device-width: 430px) and (device-height: 932px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)">
  <link rel="apple-touch-startup-image" href="{{ asset('pwa/apple-splash-1179-2556.jpg') }}" media="(device-width: 393px) and (device-height: 852px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)">
  <link rel="apple-touch-startup-image" href="{{ asset('pwa/apple-splash-2556-1179.jpg') }}" media="(device-width: 393px) and (device-height: 852px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)">
  <link rel="apple-touch-startup-image" href="{{ asset('pwa/apple-splash-1284-2778.jpg') }}" media="(device-width: 428px) and (device-height: 926px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)">
  <link rel="apple-touch-startup-image" href="{{ asset('pwa/apple-splash-2778-1284.jpg') }}" media="(device-width: 428px) and (device-height: 926px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)">
  <link rel="apple-touch-startup-image" href="{{ asset('pwa/apple-splash-1170-2532.jpg') }}" media="(device-width: 390px) and (device-height: 844px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)">
  <link rel="apple-touch-startup-image" href="{{ asset('pwa/apple-splash-2532-1170.jpg') }}" media="(device-width: 390px) and (device-height: 844px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)">
  <link rel="apple-touch-startup-image" href="{{ asset('pwa/apple-splash-1125-2436.jpg') }}" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)">
  <link rel="apple-touch-startup-image" href="{{ asset('pwa/apple-splash-2436-1125.jpg') }}" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)">
  <link rel="apple-touch-startup-image" href="{{ asset('pwa/apple-splash-1242-2688.jpg') }}" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)">
  <link rel="apple-touch-startup-image" href="{{ asset('pwa/apple-splash-2688-1242.jpg') }}" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)">
  <link rel="apple-touch-startup-image" href="{{ asset('pwa/apple-splash-828-1792.jpg') }}" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)">
  <link rel="apple-touch-startup-image" href="{{ asset('pwa/apple-splash-1792-828.jpg') }}" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)">
  <link rel="apple-touch-startup-image" href="{{ asset('pwa/apple-splash-1242-2208.jpg') }}" media="(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)">
  <link rel="apple-touch-startup-image" href="{{ asset('pwa/apple-splash-2208-1242.jpg') }}" media="(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)">
  <link rel="apple-touch-startup-image" href="{{ asset('pwa/apple-splash-750-1334.jpg') }}" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)">
  <link rel="apple-touch-startup-image" href="{{ asset('pwa/apple-splash-1334-750.jpg') }}" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)">
  <link rel="apple-touch-startup-image" href="{{ asset('pwa/apple-splash-640-1136.jpg') }}" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)">
  <link rel="apple-touch-startup-image" href="{{ asset('pwa/apple-splash-1136-640.jpg') }}" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)">

  <!-- Styles -->
  <link href="{{ mix('/css/app.css') }}" rel="stylesheet" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700;900&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">

  <!-- Scripts -->
  @routes
  <script src="{{ mix('/js/App.js') }}" defer></script>
  <link rel="manifest" href="/site.webmanifest" />
  
</head>

<body class="bg-slate-800">
  @inertia
</body>

</html>