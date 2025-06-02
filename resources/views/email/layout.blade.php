<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <!--[if gte mso 9]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml><![endif]-->
    {{-- fix outlook zooming on 120 DPI windows devices --}}
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"> {{-- So that mobile will display zoomed in --}}
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> {{-- enable media queries for windows phone 8 --}}
    <meta name="format-detection" content="date=no"> {{-- disable auto date linking in iOS 7-9 --}}
    <meta name="format-detection" content="telephone=no"> {{-- disable auto telephone linking in iOS 7-9 --}}
    <title>@yield('title', config('app.name'))</title>

    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        table {
            border-spacing: 0;
        }

        table td {
            border-collapse: collapse;
        }

        .ExternalClass {
            width: 100%;
        }

        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
            line-height: 100%;
        }

        .ReadMsgBody {
            width: 100%;
            background-color: #ebebeb;
        }

        table {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        .yshortcuts a {
            border-bottom: none !important;
        }

        @media screen and (max-width: 599px) {
            .force-row,
            .container {
                width: 100% !important;
                max-width: 100% !important;
            }
        }

        @media screen and (max-width: 400px) {
            .container-padding {
                padding-left: 12px !important;
                padding-right: 12px !important;
            }
        }

        .ios-footer a {
            color: #aaaaaa !important;
            text-decoration: underline;
        }

        a[href^="x-apple-data-detectors:"],
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }
    </style>
</head>

<body style="margin:0; padding:0;" bgcolor="#FFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

{{-- 100% background wrapper. --}}
<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#FFF">
    <tr>
        <td align="center" valign="top" bgcolor="#FFF" style="background-color: #FFF;">

            <br>

            {{-- 600px container. --}}
            <table border="0" width="600" cellpadding="0" cellspacing="0" class="container"
                   style="width:600px;max-width:600px">

                <tr>
                    <td align="center">
                        <img src="{{ asset('logo/catawol-logo.png') }}" width="260" height="48" alt="{{ config('app.name') }} banner">
                    </td>
                </tr>

                {{-- Main header (if a title is provided). --}}
                @hasSection('title')
                    <tr>
                        <td class="container-padding header" align="left"
                            style="background-color:#FFFFFF;font-family:-apple-system, 'Cal Sans', 'Instrument Sans',BlinkMacSystemFont, 'QhytsdakxAdjusted', 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;font-size:14px;font-weight:bold;padding-top:12px;padding-bottom:0;color:#212529;padding-left:24px;padding-right:24px">
                            <h1 style="margin-top: 0;margin-bottom: 0;">@yield('title')</h1>
                        </td>
                    </tr>
                @endif
                <tr>
                    <td class="container-padding content" align="left"
                        style="padding-left:24px;padding-right:24px;padding-top:12px;padding-bottom:12px;background-color:#FFFFFF">
                        <div class="body-text"
                             style="font-family:-apple-system, 'Cal Sans', 'Instrument Sans', BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;font-size:14px;line-height:20px;text-align:left;color:#212529">
                            @yield('content')
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="container-padding footer-text" align="center"
                        style="font-family:-apple-system, 'Cal Sans', 'Instrument Sans', BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;font-size:12px;line-height:16px;color:#aaaaaa;padding-left:24px;padding-right:24px">
                        <br>
                        {{ config('app.name') }} &ndash; <a href="{{ config('app.url') }}" style="color:#aaaaaa">{{ config('app.url') }}</a>
                        <br>
                        © Drew Maughan (SilentMode).
                    </td>
                </tr>
            </table>
            {{--/600px container --}}


        </td>
    </tr>
</table>
{{--/100% background wrapper--}}

</body>
</html>
