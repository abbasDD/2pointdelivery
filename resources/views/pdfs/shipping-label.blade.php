<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>2 Point - Shipping Label</title>
    <style id="reset">
        html,
        body,
        div,
        span,
        applet,
        object,
        iframe,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p,
        blockquote,
        pre,
        a,
        abbr,
        acronym,
        address,
        big,
        cite,
        code,
        del,
        dfn,
        em,
        img,
        ins,
        kbd,
        q,
        s,
        samp,
        small,
        strike,
        strong,
        sub,
        sup,
        tt,
        var,
        b,
        u,
        i,
        center,
        dl,
        dt,
        dd,
        ol,
        ul,
        li,
        fieldset,
        form,
        label,
        legend,
        table,
        caption,
        tbody,
        tfoot,
        thead,
        tr,
        th,
        td,
        article,
        aside,
        canvas,
        details,
        embed,
        figure,
        figcaption,
        footer,
        header,
        hgroup,
        menu,
        nav,
        output,
        ruby,
        section,
        summary,
        time,
        mark,
        audio,
        video {
            margin: 0;
            padding: 0;
            border: 0;
            font: inherit;
            font-size: 7.5pt;
            vertical-align: baseline;
        }

        b {
            font-weight: bold;
        }

        /* HTML5 display-role reset for older browsers */
        article,
        aside,
        details,
        figcaption,
        figure,
        footer,
        header,
        hgroup,
        menu,
        nav,
        section {
            display: block;
        }

        body {
            line-height: 1;
        }

        ol,
        ul {
            list-style: none;
        }

        blockquote,
        q {
            quotes: none;
        }

        blockquote:before,
        blockquote:after,
        q:before,
        q:after {
            content: '';
            content: none;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
        }
    </style>
    <style>
        @media print {

            .no-print,
            .no-print * {
                display: none !important;
            }

            .container {
                width: 100%;
            }
        }

        @page {
            size: 110mm 150mm;
            margin: 5mm;
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            line-height: 1.3em;
        }

        body {
            width: 100%;
            display: block;
            margin: 0 auto;
            font-size: 9pt;
            font-family: Lucida Sans Unicode, Lucida Grande, sans-serif;
            color: #000;
        }

        table {
            width: 100%;
            max-width: 100%;
            border-spacing: 0;
        }

        .txt-9 {
            font-size: 9pt;
        }

        .txt-11 {
            font-size: 11pt;
        }

        .txt-12 {
            font-size: 12pt;
        }

        .txt-13 {
            font-size: 13pt;
        }

        .txt-15 {
            font-size: 15pt;
        }

        .txt-bold {
            font-weight: bold;
        }

        .txt-italic {
            font-style: italic;
        }

        .txt-gray {
            color: #bababa;
        }

        .txt-align-left {
            text-align: left;
        }

        .txt-align-right {
            text-align: right;
        }

        .txt-align-center {
            text-align: center;
        }

        .txt-color-gray {
            color: #8c8c8c;
        }

        .txt-color-white {
            color: #fff;
        }

        .flex {
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
        }
    </style>
    <style>
        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            line-height: 1.3em;
        }

        body {
            width: 100%;
            display: block;
            margin: 0 auto;
            font-size: 8pt;
            font-family: Lucida Sans Unicode, Lucida Grande, sans-serif;
            color: #000;
            word-wrap: break-word;
            word-break: break-word;
        }

        table {
            width: 100%;
            max-width: 100%;
            margin: 10px 0;
            border-spacing: 0;
            font-size: 1em;
            vertical-align: middle;
        }

        td,
        th {
            vertical-align: top;
            text-align: left;
            padding: 0;
            margin: 0;
        }

        table th {
            word-break: normal;
        }

        hr {
            margin: 0;
            border-top: 2px dashed #bababa;
            border-bottom: 0;
        }

        hr.solid {
            border-top-style: solid;
        }

        h3 {
            margin: 27px 0 5px;
        }

        .container {
            width: 115mm;
        }

        .page {
            width: 45%;
            min-width: 110mm;
            max-width: 110mm;
            position: relative;
            display: inline-block;
            vertical-align: top;
            padding: 0 0.23in 0 0.23in;
        }

        .page.dotted {
            border-right: 2px dotted #bababa;
        }

        /*table.border {*/
        /*border: 1px solid #888;*/
        /*}*/

        table.border td {
            border: 1px solid #bababa;
        }

        /* Page Breaker */
        .page-breaker {
            position: relative;
            text-align: center;
            page-break-before: always;
            margin-bottom: 20px;
        }

        /* Cut line */
        .cut-line {
            display: none;
        }

        .shipping-label.cut-line {
            position: relative;
            display: block;
            margin-top: 15px;
            left: 0;
            border-bottom: 2px dashed #bababa;
            width: 104%;
            height: 0;
        }

        .shipping-label.desc.cut-line:after {
            content: 'Potong atau lipat pada garis ini';
            font-size: 0.8em;
            margin-left: 1em;
        }

        .scissors_icon {
            transform: rotate(-90deg);
            bottom: -14px;
            vertical-align: middle;
            position: absolute
        }

        .scissors_icon.vertical {
            transform: none;
            display: block;
            position: absolute;
            top: 0;
            right: -12px;
        }

        .barcode__text {
            margin-top: 10px;
        }

        table.pricing {
            margin: 0;
        }

        table.pricing td {
            padding: 3px;
        }

        .payment-item {
            margin-right: 8pt;
        }

        .payment-item:before {
            content: '';
            display: inline-block;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            width: 16px;
            height: 16px;
            margin-right: 4pt;
            border-radius: 2pt;
            border: 1pt solid #333;
            vertical-align: -3pt;
        }

        .payment-item-selected:before {
            display: none;
        }

        .payment-item-selected img {
            width: 16px;
            height: 16px;
            margin-right: 4pt;
            display: inline-block;
            background-size: 16px 16px;
            vertical-align: -3pt;
        }
    </style>
</head>

<body class="container">
    <div class="page ">
        <div class="$courier-label">
            <table class="label-card">
                <colgroup>
                    <col style="width: 40%;">
                    <col style="width: 60%;">
                </colgroup>
                <tbody>
                    <tr>
                        <td style="vertical-align: top;">
                            <div>
                                <div class="txt-13 txt-bold" style="margin-top: 10px">2 Point </div>
                            </div>
                        </td>
                        <td rowspan="2">
                            <center>
                                <img src="data:image/jpg;base64,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"
                                    width="100%" height="40" class="barcode__image" />

                                <div class="barcode__text txt-9">
                                    AWB No. JP9365780159
                                </div>
                            </center>
                        </td>
                    </tr>
                </tbody>
            </table>
            <hr />
            <table class="$courier-label border" style="">
                <colgroup>
                    <col style="width: 45%;">
                    <col style="width: 55%;">
                </colgroup>
                <tbody>
                    <tr style="height: 20px; line-height: 25px;">
                        <td class="txt-align-center">
                            NON COD
                        </td>
                        <td class="txt-align-center txt-16">
                            4 kg
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 8px 5px; line-height: 1.5" class="sender">
                            <div><b>SENDER</b></div>
                            <div class="name">INDONESIAMALL</div>
                            <div class="phone">08155556586</div>
                            <div class="address" style="margin-top: 10px">
                                <div>Suryodiningratan - Mantrijeron</div>
                            </div>
                        </td>
                        <td style="padding: 8px 5px; line-height: 1.5" class="receiver">
                            <div><b>RECEIVER</b></div>
                            <div class="name">Oriana paramita dewi</div>
                            <div class="phone">6281542222291</div>
                            <div class="address" style="margin-top: 10px">
                                <div>
                                    Jalan Gondang Waras No.17C, RT.10 RW.04, Sendangadi, Mlati, Sleman, Yogyakarta, KAB.
                                    SLEMAN, MLATI, DI YOGYAKARTA, ID, 55597
                                </div>
                                <div style="margin-top: 5px;">
                                    Sendangadi, Mlati, Sleman
                                </div>
                                <div>DI Yogyakarta</div>
                                <div> - 55597</div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table>
                <colgroup>
                    <col style="width: 45%;">
                    <col style="width: 55%;">
                </colgroup>
                <tbody>
                    <tr>
                        <td style="vertical-align: middle">
                        </td>
                        <td>
                            <table class="pricing">
                                <colgroup>
                                    <col style="width: 55%;">
                                    <col style="width: 10%;">
                                    <col style="width: 35%;">
                                </colgroup>
                                <tr>
                                    <td style="text-align: right;">Ongkos Kirim</td>
                                    <td style="text-align:center">:</td>
                                    <td style="text-align: right"><b>Rp 0</b></td>
                                </tr>
                                <tr>
                                    <td style="text-align: right">Asuransi</td>
                                    <td style="text-align:center">:</td>
                                    <td style="text-align: right"><b>Rp 0</b></td>
                                </tr>
                                <tr>
                                    <td style="text-align: right">Total Ongkos Kirim</td>
                                    <td style="text-align:center">:</td>
                                    <td style="text-align: right"><b>Rp 0</b></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>

            <hr>
            <table class="$courier-label">
                <colgroup>
                    <col style="width: 45%;">
                    <col style="width: 10%;">
                    <col style="width: 45%;">
                </colgroup>
                <tbody>
                    <tr>
                        <td>
                            <img class="pca-logo" style="max-height: 40px; max-width: 100%;"
                                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAVEAAAAyCAYAAADlYegCAAAACXBIWXMAAA6eAAAOngF4apBUAAAYWElEQVR4nO1d3W8bV3b/kZb8teslx1YQ7zqJxlskKtog0mIXKlKg1biOFujDlsyD+lRA9F9g+ql8M/XGt9BA3/rg0bMeQqIPBSq4HqJoC6gtQmGB7Wo3jUcJNrETyUMmsS3bEtWHe4a8c3nnziU5tORkfgABzp37ce7Xueece+6d1OHhIYaFdWd3DkARQNG5eqE1dEYJEiRI8JIiPUpi5+qFJoAmgCYx1AQJEiT4XiE1iiTqw7qz6wLIgkmk9sgZJkiQIMFLAi1JtHN/Ktu5P5VVRCkDyAC4bd3ZrcZBWIIECRK8DNCWRDv3p2oA7PTFnZrsvXVntwXGSAFg1bl6oRALhQkSJEhwjDGITbQIwO7cnyqHvLe5/8vWnV07JF6CBAkSfGcwkE2UGOhNAA0A+fTFne6OvHVnNw/gQyHJWCXSimFkAehuaLklz3PHRUuC0VAxjDmwvjQlr5sAnJLnJR4gCY4dBmWiWQAumNq+CcZIXQCw7uyaAO5Jko2FkVYMwwJQQ8+EoIM2AAdAreR5dtw0JRgMtAgWARQATGskaQCwk75LcJzQZaLe4kwWQMFY31JuDHXuT9kAlumxDcBKX9xpAoB1ZzeMI8fOSCuGUQaTiodFA0AhkU6PBhXDyIOZgAZZBH1sg/WdEydNCRIMg65N1FjfagGAtzjjEEMNA7+xlAHgdO5P+Sp1IyTNsnVntzgSpfFjAUCT1MgELxAVw6iCmX5kDLQNNo78X1sSZxpAjSTZBAmOFBP8g7G+VfUWZwoAXG9xxjLWt5pigvTFnVrn/hQf5DNSC9hSlfWBdWfXda5ekO7uj4qS56XC3hGjNAHk0ZOiAUa7DX27aoIRQRrEdSG4DaAKpqq7kjRZsL4rgC1+AOu7LIDETprgSNFnE/UWZywAd+nxmrG+ZYuJOvenmgBmheD2r3773x8/Ojj3c0V5bQBzztUL7rAE+xDVeRUTFdLNgUnTvA3uWmJnGz/Ijn1XCF4FUNTdNKI8LLCNJidG8hIkGAp9Lk7G+pYDZnMCgNve4ozMRupKwjLvnvu31ydS+6ryfMnvyFDyvCaYVMNDfE4wHtjC82rJ8wqD7LqXPM8peV45YaAJjgsmQsJt9KS8697iTNZY3ypw75sAcmKiyfTzyflz//lw45t3z+8fhmWNBevObtG5euHITjaVPK9ZMYxN9KRppTpP6mQBTALKgqmU22CLSRNst9+JyKMAxqztkucpTRokLRfBVNVyFJPh8q6q6KgYhsnVwwSTxhtUjkO0RZXl76ibRJvL5V1Gz02pWvK8skAjL/03Sp5XUJU1KmjzKk/0mBiivpSPCVY3F6xeLQovcPnPUt4u5etI8vHj+2NoE2z8SOMLabNEA8CNCZLMC0SDn6dPZxgNlm78CJrEvAaaExz9RQgeM5R3gfJ2wbSVPvNiCC3+fG6C9bW2Nw7NvQJ645gfM9J8pC5O3uLMHICPhOBVn5Fy/qJdPHj+Gv7xy78HAHz1/NWHv340e15B68hq/bDq/KDpKV4R0bvIm2A7xn0dTR3jt2cbgKmavBXDcNFjOKsqZqObN23miLZIEW2qQyiTrxhGEcAH9FgveV5e4SlxmWOyNQQX3u67uEET00a021QbArMPyc9BzxZ7reR5dsUwbATt6yJulDyvSullJiQRKyo6hPJulTyvqEFDd+zQQlBDvxlOGl8Fqo8dkRdA/uQDjPUrYAxTRme95Hl9GiMxzyqi5+c22OJjh9CRBatTn3AoySfgGSI9sSTbUAKw7C3OSAkAgMbXf/3A///K5IPzb5xy9xSEZNBbVZUghj4OmKqXFcPI0uS5CT03nFkAH1GnqsrKgK2YYeXmEZxsyxG70Hx5UjorhtFENAP1039ITDEMPC05msgyBtpGcNOHH5z1MTLQApjdVcfvNAPgJtVBhQXuv6nBvADgg4phFIjhOBr03KS+D4PJ/Z/TpGG5YhhVYqCyfQxZ/LIqAlefqLwA1m5uhAcM3y4FhNPpSmixAdyG3vycBnBb1sY0vxxEM1A/n7v8PFcd+5S5Ky3LbKStg/P43d47r/Jhf3Tm950fnvhGRcwyOehHYVxuLBb3X1bXKoKTB2Cr0CqAGwBWQtLdlgwaR3hWTRZLEqaKz7/bFFd9kkD5QdkGcAvA+2Ar/zWwOvGImtA8+Im8SXmvALAElZOHo5n3QKBybkteNYimGwDq6Nn8fSxTO+ngJnp13qZ834d8PFQRPBASFd/WpGFBQsO1kDyvS2i4pYh/k5huHyjcQZBpbVI+V+i3gmD7ZgDYmu5oy1zebbC+8utWFmix0b+I+HVb4X6bQhxLUq4o+bbR66crYONGbKfuPA81XCLcdeT6wacTmyfe6G0g/ZP3dzsAAn5PKRyefecHH+38x9d/OSVmwKFIPxX8lS82kErKr4BN4b2F/g6Sqjohqo0Nzs5a8rxWxTDq6K10ukzRhwXJBKNBzdfDlrznJdA2GHMTNQ2bpG6eAfkMQAdtMLXNCXkvTqBQ29aIsIXnTXDMnOCr2DaCfXy9YhhadjyCqH7XKF/ebJFBjyloxa8YRj7KZs4hbEyWEdQO/LEp9YSQxM+D2klAGUEGKsvPoQXJ4cqdBZvnZUVdeCg9NmiBF+dn13wi0szZjf06iHnxwpLMLOcAqEpMYlUAlkoSDR3oB/91+o8PWyzpR4/+/Mn20zeljPJ0em8qQq3XkXZik0RJRS+jZ9PzITZ+WXheCbMVUWNbCK54sxJJjp8YGZmKQ2EytS+snVRlAMF6hDFQAADZiq5xQdMhpgkRfr6OIo5Y19iZqGTjSsZAu6D+FCXwqAXdh8p+KQu/pYgvlqlrvlLZL2XMpK7whBDjW2IEkiR5xhWaH4VZCEpvYbSK0PHYEOm9EsJAfXocylOWL0/XNtRzpAgmofpYqBjG3MA3209MHzwBcOr53bN7rb0L+Of23yoP3//0zMeHCrenadWN+BEnpwKoGEY54ueA2VVE+90t3j5H0hu/Mq1GbTxQx4gMLS/EsRE8fVOQZMWH8Uw5E6JeB+JL7IwW97+m2t3kaOTL1VnkQgedohzVBBkWfe0fVQ4xIX6i5zTUzoZqPEjaYpsmX1h8VwjSYaLbUDB8qjffj9tQMDGKz7eDrA3E9lUuOJQnz9imNU4H1qM2tkiq5BfL1WHd3aiveTtoVWPMlBGcx/mBmGhqEph4e/8ZAKCD0//y+196ncMTZ1Vp0uicuXTqM08RxVK8012VAcYcVb8F9BugVyUD3BSetVQrmgz8QLQk0fi8wtR2HzaYTUganwaAaELg35sIDjZdFdHh/ke2/6AMFOjSFjcs7v8gG1eiBBNVZ0cjT36S6dARxcBEuBoLEf9eJ34U+HbZ1mlfiVnCikiiM5bEPMoaacIwrIbEx7NUNtG+wTT59v5OapLZPu+du/Rk//mekXn86V777BunVSVOn3LT23uXtcvRfDcK2mA2F1vyzhKenQHyddCTYmVqeQ09lWi6Yhim4Gc5K8RtobdSinSJTNgWnk3h+cOKYYTRHQadHW4dOAhqACb0mMsg4BfIQRi7GDcOG3wT/ZuSLzv4uThdMYxhvisUh2nO5P63R/TyEPnL3SHmiHJ3PlBAeqqDE9MHXdvnr8+/+QwAzn/zf52JA5XZE5hIPc8odurNCBocZeY9NDR+twC8X/K8rK7zbZyqJ63MAVUg5L+vmvMruagO8fHrY1KRZZd/DAORNiumfEeGZBLGZoNP0Ic4xqjJ/R/Vth5LX0slUTo/35VCUpPAyT973gat9vfOXXryLD2ZAdgu/Ctf/+/OF8bPVLvwuHjy8wcfP5l5VfJKVZE5aDZ8yfMsnXjHALw0mkdPneSZog1Id/UL6NmiLCHPuLGN0VSlLuiEWHf8gNUjlrxHhabrTYLR0UD849SMOb9BsQ2gHKbOl/kHXo0HgHvnLu0AeN1/PvPMmzq5/y2eTfwwtLSpiZ39jzEjeyV12qVNpdkQx/9xwuUfKoYRtfPMw9SIwzPRBW4S8+qfI8QPqPS0yZQR4kThqC9ZEU0Zg7jyDApzgLhj9xz4joAXZtolzzuqxccBZzKrGEZ2BC1M7Osrw2xS9anz3uJMHtyETmcOA2o8ADw8lXldTGd8+8kDMYzHyfRT5QaUBHn0O8q+CIgNq7NDLYsrvVs1RKXn020LmzU8o5kl22mkKi8ZDIPUYxwQN3CqMUuBUZt6YRDbxR2Zku8m+DEpddF7QXCF51HGdSx5BZgoSX82HzY5u/8Z/9w6eU6a0dlnD3+kKmgitR9msQ1jlBbGdLJFBWJg/ImLgs6AIedtXemQf2ch2HmBdBJ3lTz0VXk+Xe4IB77frry3wTSYY3ZcjFS0H9tRCbjLJnyIC1iCHsRxJi6KLwoiHeVhxxD1dcDtcJi8REnUBscIUmcPkT7fCUidz9NyC0DqsHPmB0+/UhZ2Ov1EFhwmiudxBEyUYHP/M2CTPZQBSU6/tKE+wie6OlkhZcvCytB3XRIHek1zQZgbk62wgOCgnQX7uoClk7hiGCb5/DYlTFJsh2UVI+XOgPML31ExhmMPiXCxoLlQZeNcvEmouMUFaS3GNKZldPB97s/1yLFfMQzLj9fliN7iTBHCAfwTr3U8ANp7/qefeduPTr0S6hZzJv0Ee50zYnDfyk+368NY3xqXzSwKVQQ/nuY3bhV0GTCp1RYYExQvLlA67ZY8r8ZttPCTOEwSqqF3yoqPr9yVp9uGiujZnf0BZ4O7qozqYqJnWpim8FhvWqKNMgtB5uVf6LAJtlg0QV9mrfS+5joH1tZ8O89WDKPqtxfFX0HQlcq/vKVGZba4vIoQzoCrTr0kAMDajP+i7zKNHRtsXrhAdzz5Un6OwrRuiNJEGdw4BRvfLs3P7qESGmsm0T1LYeLeQBXBsRCW1xzYJnie6pUB0K4YhjkBdNX4skjpxOsHjzEAEz377OHErm7kHhxJWAHj2XHWAk12XxL2GzcDctyP8CWLPOFEsNF/s5K0zsQg+PtPlfEFFNBfj+tgZ8Wj0s4hZhsh7dRb6JcCZ8Edx9WgrSEuOCXP889J85t0OUTfztOG/rHE7y1o8V9FUOtaoF9Un8UqjY4wP01JXgUEFwfdvDIAsr46L67KAIDUucNLYlj2WfjNTBMHe6o7RPHNQZ/ZtC1+c4ncqxYwGBMVb+UZGdyZ+EHyvjXAamtrhoW9a0OjjYasB8DqEpX/UO1ONJnoP7uui1Wo7xMYJF//jL3KFjrK+HJjit8K+R+GUeKHgsb3SlQ8AbqLlBYNREcTjDFLN3BD0IDEZEPj/AoG94u+UfI8N01SaFE31WRnH+eftj+TvUsd9uvqPCS33cvUpzKAbQ1VvgY2uP2PnMUOrqNuQO0psArmHqHdjpT3Chj9bbBLLUInMqmaPnPwL4bVHfjNkueZYBeMqOrhX5N3WVGXWNq95HktmpCXqcwoRtUA6wdDdUEFl+8VBDeyRGyCuX3NaWwmVcHquw296+psLr5OG/Hxw/L34+i2Ox8/LE8efh0j8ydN6zLYeFQxHr+Ns4o2XuHi6tDJ0+GSf/g1qPu6ATY/VRfSOGALu3iVnwj/6kHDN/+kHr73VgHyOxhxJv9UmstXpw00fvwL6btPXv0rafjTzqkH//71Au9svw12u323UtxH8m4Y61vHzj7F2eh8tF7W3VzJZo4bp/1zGEjaF4ihjTl7lo/mmE54fS/B2dR5HEkbi+N6xMtJxLEonSOph++9VUOIzSiMiQLA/0z9yc69c5f6TimFMdHPn13a+e3jP/XjtwFYztULgcnhLc44RLhprG8lgzxBggTHHmkojO6H36T+EPbu5zu/mbr06MuHugW5ez+NYqBFMFtoMWGgCRIkeFmgvApv/+MTyo2id7/cPP/Ow9/tpQ8PH6vifXtwbodcm+oATAkDNcFsoZuy79wnSJAgwXGF6io87G+fODPx9n47NRn+Iai32tunLz36Ep/86LX21vk39wFcEKI8+c3jtxsA/sG5esEJycYG8w4oaFOeIEGCBMcAqYfvvaW8FzCdOcSphWePkUbk2ffJXz7+w5enf3LJd6g/ld779uLkZ3+RvrgTujFAH767DmDFWN8qD0Z+ggQJEhwtUg/fe6uFiE+OnvhJ5/HJXzxPIY1wF6bJQ5z8m0d8SBuAFcFAC2CeAQ1jfcsagG4AwHxuqQDA2qivFSTv8gCKG/U1Zb7zuaUqJI7AGukcyr8phGfBJOuy+E6IN4egK0kL7LSOvVFfc8dIsw+XKy8WG/R8bskEc5fzaWsCqEbVR0KbD3ujvmaPSFMBQQ2nCaC2UV+TlSemzVJa3ydVK+18bskG0Nyor1W5sDIAkx+rNEYLG/W1MJ/XuPq6BeaYHmdfy9omsq8l9XEAOCP0xyDjKzBfaWzMbdTXlK6JUW2ZhobD9sHn6bNPGyfPdL5Ohd7UdGLmOe8vVgdgajLQTQx/E4sJYHk+txRoBGrsKvRuF5+jfBzhF4UFhPu55hB94WuW8vDLa6L33e0o+IPQweA0u+gdgSwDcKi9RgItCk307jxw6H+T3g1Cm/9zR6ULrG9NLk8TwF1xzIigNnHA2qhJ/+cobSGizBb6fa+LYGPV5MIKiHYwH2V8uuiNrSIAN8a+dinPJv0vALgn1E8GsT4WWJsqeYCkTD/tPY3+AFh7iHU3oXeSStmWE2BSk/j50T502ik8/deTr574cQcTbx58xl9MkjrbwYnLzz0whlxVMU8gwEDbAKwYduPL87klfpUtY7BPW7gb9bXyEOUuzOeWir7EQR0tHuVUgi93PrfUhL7DsTMkzba/6pNUcA/xfBKjBmKifj9Q/jX6mYPQFjNcoZ1thH8W2EcVxIB56Y3S3p7PLTkKCcgGcH0+tzS3UV9rEoPIgD4tDaBKzCYH9m3zgegfAGJfe2DMTlVvHVTBmIrF9XUWrK1cjfRif/gChEqg6ysTbN7XwNqzFpeUHYLQtpww1rccb3HmFjQn/8EXaRx8ke4y0HTmEJ12asW49mlZJ723OFMGO5e6CaAQAwNtgK0wNoA8x8ga0P/OjUnqlg8t9YLK4Bl4FUwKjzqr3YVQbgH6N75bQ9IcO6jNp8FU025/btTXWjTgPvQZSkRWc/O5pe7DUdWHkAdTFcXxWQQTOiyELHjEOP0vchbotwomdRbAxkkeQHujvhapCWL48TkuLAC4JvY1xnuhdV+ZhCLiEwSGwgQAGOtbRTotJL1lXoVOO7WqsyFEx0urYANwE/FIoD6KYCqBBcaE6ngxHwsrg9XJJruJr6qovm4aBl/V0FEvRkGB2glgE3oT4/1WzSB9/IHwnBqCFhl4JmSCjcGo89/SfQJaGIBoc00NrK2z6EmcLkhCxYu5ZEfsa90joDoI9CuVc3ejvqbTZ3x/WGB8pxwWOaxMRZguBjFthLZl18XJWN+a8xZnbGio9oQ2gLLO8UxvcWYObMBMg63IsTrUb9TXnPnc0i2wI6Nt9K6/0sWw6hLAGvQjsMFQ5iaZFoRyi/O5pdZ8bimvIaEMq877jN4EG0TWqGoQtX8brM0d4XURTOLSYdRXxiRhZdG7s9XVLKcOJi2W+UDOlhqVvgqmEdXASZwkoZbBGEchknKiecS+zoLNvcsxqby+lD3sIsD3xwKAGxrjPaxMvz+ixpdvSnG4MEsjnQ+/LQHGPLvmw4CfqLG+VSBGWka4FOffHlQ21rdcVancFXvXKd04z8SXQerwoIwMQJZbZQDoq5Kkut0C2+UbuG5CuRboei2NpOaQNBeJ6WXBGEoZ8fjnFsFshTUwBpJF7z7JazHkPwqaUbvZEpTBNt2a9L8FNgmvA1iNWhQ26mvufG5pE2we8ZcIV8Ek7m3NhQWQjE+wOkUxRL6vHbDJL+YzDIpgJhoHvb7OD5C+2x9kYxb3NHTK9E0jywBWNNrC1wyaINsq2EKmK2wVw+ZXn7O9sb7lALBIerQgXNwAwNGRImnzqArGFBpg9k9Xk2BduESTb5PJCu90rslqgq0yZSHcikjXAKkSEheJ7jsFWhRPLHdFw60nDppbtOFR1rRXKrFRX7Pnc0suyLRCwXXoS5c6bTYMXAxhrqDF0QKrjw02jjfB7HK2ZjZVsInOx6+BMRxdKS6sr4tQ10vs6wLYBoyOlqPERn2tNp9buoJg2zSgt1iKNBfB5m0RCpWeyvwZxeHLfF+zPkX0PFKmwfry/TjGZurwUOlrPxC4a/UK6BFaJMacIEGCBN85KI996oK+EJpHz55aB5M8nTjyT5AgQYLjioElUZI2fVXfQs926l+qWhuD2p4gQYIExxIBJkoSZQtB25qJ3q4UzzBdMPuGtp00QYIECb5r+H8pijpi9RaL2wAAAABJRU5ErkJggg==">
                        </td>
                        <td></td>
                        <td class="txt-align-center">
                            <div class="delivery_id">
                                <img src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD//gA+Q1JFQVRPUjogZ2QtanBlZyB2MS4wICh1c2luZyBJSkcgSlBFRyB2OTApLCBkZWZhdWx0IHF1YWxpdHkK/9sAQwAIBgYHBgUIBwcHCQkICgwUDQwLCwwZEhMPFB0aHx4dGhwcICQuJyAiLCMcHCg3KSwwMTQ0NB8nOT04MjwuMzQy/9sAQwEJCQkMCwwYDQ0YMiEcITIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIy/8AAEQgAHgDgAwEiAAIRAQMRAf/EAB8AAAEFAQEBAQEBAAAAAAAAAAABAgMEBQYHCAkKC//EALUQAAIBAwMCBAMFBQQEAAABfQECAwAEEQUSITFBBhNRYQcicRQygZGhCCNCscEVUtHwJDNicoIJChYXGBkaJSYnKCkqNDU2Nzg5OkNERUZHSElKU1RVVldYWVpjZGVmZ2hpanN0dXZ3eHl6g4SFhoeIiYqSk5SVlpeYmZqio6Slpqeoqaqys7S1tre4ubrCw8TFxsfIycrS09TV1tfY2drh4uPk5ebn6Onq8fLz9PX29/j5+v/EAB8BAAMBAQEBAQEBAQEAAAAAAAABAgMEBQYHCAkKC//EALURAAIBAgQEAwQHBQQEAAECdwABAgMRBAUhMQYSQVEHYXETIjKBCBRCkaGxwQkjM1LwFWJy0QoWJDThJfEXGBkaJicoKSo1Njc4OTpDREVGR0hJSlNUVVZXWFlaY2RlZmdoaWpzdHV2d3h5eoKDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uLj5OXm5+jp6vLz9PX29/j5+v/aAAwDAQACEQMRAD8A8/8ACX/JIfiL/wBwz/0oavf/ABb/AMle+HX/AHE//Sda8A8Jf8kh+Iv/AHDP/Shq9/8AFv8AyV74df8AcT/9J1oAPjb/AMkh13/t3/8ASiOvP/8Am7z/AD/z4V6B8bf+SQ67/wBu/wD6UR15/wD83ef5/wCfCgD0Dxb/AMle+HX/AHE//SdaPFv/ACV74df9xP8A9J1o8W/8le+HX/cT/wDSdaPFv/JXvh1/3E//AEnWgDz/APaa/wCZW/7e/wD2jXAfG3/kr2u/9u//AKTx13/7TX/Mrf8Ab3/7RrgPjb/yV7Xf+3f/ANJ46AD42/8AJXtd/wC3f/0njrv/AIgf81i/7gv/ALLXAfG3/kr2u/8Abv8A+k8dd/8AED/msX/cF/8AZaAPQPgl/wAkh0L/ALeP/SiSvP8A/m0P/P8Az/16B8Ev+SQ6F/28f+lElef/APNof+f+f+gA/Zl/5mn/ALdP/a1cB4S/5JD8Rf8AuGf+lDV3/wCzL/zNP/bp/wC1q4Dwl/ySH4i/9wz/ANKGoAPjb/yV7Xf+3f8A9J46Pjb/AMle13/t3/8ASeOj42/8le13/t3/APSeOj42/wDJXtd/7d//AEnjoAPFv/JIfh1/3E//AEoWj42/8le13/t3/wDSeOjxb/ySH4df9xP/ANKFo+Nv/JXtd/7d/wD0njoA9/8Ajb/ySHXf+3f/ANKI6PCX/JXviL/3DP8A0naj42/8kh13/t3/APSiOjwl/wAle+Iv/cM/9J2oA8A8W/8AJIfh1/3E/wD0oWvr+vkDxb/ySH4df9xP/wBKFr6/oA+f/wDm0P8Az/z/ANegfBL/AJJDoX/bx/6USV5//wA2h/5/5/69A+CX/JIdC/7eP/SiSgDz/wCIH/NYv+4L/wCy16B8Ev8AkkOhf9vH/pRJXn/xA/5rF/3Bf/Za9A+CX/JIdC/7eP8A0okoA8A+CX/JXtC/7eP/AEnko8W/8kh+HX/cT/8AShaPgl/yV7Qv+3j/ANJ5KPFv/JIfh1/3E/8A0oWgA8Jf8kh+Iv8A3DP/AEoavf8Axb/yV74df9xP/wBJ1rwDwl/ySH4i/wDcM/8AShq9/wDFv/JXvh1/3E//AEnWgA+Nv/JIdd/7d/8A0ojrz/8A5u8/z/z4V6B8bf8AkkOu/wDbv/6UR15//wA3ef5/58KAPQPFv/JXvh1/3E//AEnWjxb/AMle+HX/AHE//SdaPFv/ACV74df9xP8A9J1o8W/8le+HX/cT/wDSdaAPP/2mv+ZW/wC3v/2jXAfG3/kr2u/9u/8A6Tx13/7TX/Mrf9vf/tGuA+Nv/JXtd/7d/wD0njoAPjb/AMle13/t3/8ASeOu/wDiB/zWL/uC/wDstcB8bf8Akr2u/wDbv/6Tx13/AMQP+axf9wX/ANloA9A+CX/JIdC/7eP/AEokrz//AJtD/wA/8/8AXoHwS/5JDoX/AG8f+lElef8A/Nof+f8An/oAP2Zf+Zp/7dP/AGtXAeEv+SQ/EX/uGf8ApQ1d/wDsy/8AM0/9un/tauA8Jf8AJIfiL/3DP/ShqAD42/8AJXtd/wC3f/0njo+Nv/JXtd/7d/8A0njo+Nv/ACV7Xf8At3/9J46Pjb/yV7Xf+3f/ANJ46ADxb/ySH4df9xP/ANKFo+Nv/JXtd/7d/wD0njo8W/8AJIfh1/3E/wD0oWj42/8AJXtd/wC3f/0njoA9/wDjb/ySHXf+3f8A9KI6PCX/ACV74i/9wz/0naj42/8AJIdd/wC3f/0ojo8Jf8le+Iv/AHDP/SdqAPAPFv8AySH4df8AcT/9KFr6/r5A8W/8kh+HX/cT/wDSha+v6APn/wD5tD/z/wA/9egfBL/kkOhf9vH/AKUSV5//AM2h/wCf+f8Ar0D4Jf8AJIdC/wC3j/0okoA8/wDiB/zWL/uC/wDstegfBL/kkOhf9vH/AKUSV5/8QP8AmsX/AHBf/Za9A+CX/JIdC/7eP/SiSgDwD4Jf8le0L/t4/wDSeSjxb/ySH4df9xP/ANKFo+CX/JXtC/7eP/SeSjxb/wAkh+HX/cT/APShaAP/2Q=="
                                    alt="" width="100%" height="40" class="barcode__image">
                                <div class="" style="margin-top: 5px;">
                                    <span class="txt-9">Delivery No.</span>
                                    <span class="txt-9" style="margin-left: 10px;">DLV15265</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="$courier-label border">
                <tr>
                    <td style="padding: 10px;">
                        <div>Isi Paket :</div>
                        <div style="margin-top: 10px; max-height: 90px; overflow: hidden; line-height: 1.2">
                            ADS-TBM-001 (5 pcs),<br />
                            DRR-KPB-002 (1 pcs)
                        </div>
                    </td>
                </tr>
            </table>

        </div>
    </div>
    </div>
    </script>
</body>

</html>
