<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
         @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url("{{ public_path('fonts/THSarabunNew.ttf') }}") format('truetype');
        }
        body {
            font-family: "THSarabunNew";
            margin: 20px;
        }
        .flex {
            display: -webkit-box;
            display: -webkit-flex;
            display: -moz-box;
            display: -ms-flexbox;
            display: flex;
        }

        .inline-flex {
            display: -webkit-inline-box;
            display: -webkit-inline-flex;
            display: -moz-inline-box;
            display: -ms-inline-flexbox;
            display: inline-flex;
        }

        .flex-row {
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -webkit-flex-direction: row;
            -moz-box-orient: horizontal;
            -moz-box-direction: normal;
                -ms-flex-direction: row;
                    flex-direction: row;
        }

        .flex-row-reverse {
            -webkit-box-orient: horizontal;
            -webkit-box-direction: reverse;
            -webkit-flex-direction: row-reverse;
            -moz-box-orient: horizontal;
            -moz-box-direction: reverse;
                -ms-flex-direction: row-reverse;
                    flex-direction: row-reverse;
        }

        .flex-column {
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -webkit-flex-direction: column;
            -moz-box-orient: vertical;
            -moz-box-direction: normal;
                -ms-flex-direction: column;
                    flex-direction: column;
        }

        .flex-column-reverse {
            -webkit-box-orient: vertical;
            -webkit-box-direction: reverse;
            -webkit-flex-direction: column-reverse;
            -moz-box-orient: vertical;
            -moz-box-direction: reverse;
                -ms-flex-direction: column-reverse;
                    flex-direction: column-reverse;
        }

        .flex-wrap {
            -webkit-flex-wrap: wrap;
                -ms-flex-wrap: wrap;
                    flex-wrap: wrap;
        }

        .flex-wrap-reverse {
            -webkit-flex-wrap: wrap-reverse;
                -ms-flex-wrap: wrap-reverse;
                    flex-wrap: wrap-reverse;
        }

        .flex-nowrap {
            -webkit-flex-wrap: nowrap;
                -ms-flex-wrap: nowrap;
                    flex-wrap: nowrap;
        }

        .flex-justify-start {
            -webkit-box-pack: start;
            -webkit-justify-content: flex-start;
            -moz-box-pack: start;
                -ms-flex-pack: start;
                    justify-content: flex-start;
        }

        .flex-justify-end {
            -webkit-box-pack: end;
            -webkit-justify-content: flex-end;
            -moz-box-pack: end;
                -ms-flex-pack: end;
                    justify-content: flex-end;
        }

        .flex-justify-center {
            -webkit-box-pack: center;
            -webkit-justify-content: center;
            -moz-box-pack: center;
                -ms-flex-pack: center;
                    justify-content: center;
        }

        .flex-justify-between {
            -webkit-box-pack: justify;
            -webkit-justify-content: space-between;
            -moz-box-pack: justify;
                -ms-flex-pack: justify;
                    justify-content: space-between;
        }

        .flex-justify-around {
            -webkit-justify-content: space-around;
                -ms-flex-pack: distribute;
                    justify-content: space-around;
        }

        .flex-justify-evenly {
            -webkit-box-pack: space-evenly;
            -webkit-justify-content: space-evenly;
            -moz-box-pack: space-evenly;
                -ms-flex-pack: space-evenly;
                    justify-content: space-evenly;
        }

        .flex-align-start {
            -webkit-box-align: start;
            -webkit-align-items: flex-start;
            -moz-box-align: start;
                -ms-flex-align: start;
                    align-items: flex-start;
        }

        .flex-align-end {
            -webkit-box-align: end;
            -webkit-align-items: flex-end;
            -moz-box-align: end;
                -ms-flex-align: end;
                    align-items: flex-end;
        }

        .flex-align-center {
            -webkit-box-align: center;
            -webkit-align-items: center;
            -moz-box-align: center;
                -ms-flex-align: center;
                    align-items: center;
        }

        .flex-align-baseline {
            -webkit-box-align: baseline;
            -webkit-align-items: baseline;
            -moz-box-align: baseline;
                -ms-flex-align: baseline;
                    align-items: baseline;
        }

        .flex-align-stretch {
            -webkit-box-align: stretch;
            -webkit-align-items: stretch;
            -moz-box-align: stretch;
                -ms-flex-align: stretch;
                    align-items: stretch;
        }

        .flex-content-start {
            -webkit-align-content: flex-start;
                -ms-flex-line-pack: start;
                    align-content: flex-start;
        }

        .flex-content-end {
            -webkit-align-content: flex-end;
                -ms-flex-line-pack: end;
                    align-content: flex-end;
        }

        .flex-content-center {
            -webkit-align-content: center;
                -ms-flex-line-pack: center;
                    align-content: center;
        }

        .flex-content-between {
            -webkit-align-content: space-between;
                -ms-flex-line-pack: justify;
                    align-content: space-between;
        }

        .flex-content-around {
            -webkit-align-content: space-around;
                -ms-flex-line-pack: distribute;
                    align-content: space-around;
        }

        .flex-content-stretch {
            -webkit-align-content: stretch;
                -ms-flex-line-pack: stretch;
                    align-content: stretch;
        }

        /* Flex Items */
        .flex-self-auto {
            -webkit-align-self: auto;
                -ms-flex-item-align: auto;
                        -ms-grid-row-align: auto;
                    align-self: auto;
        }

        .flex-self-start {
            -webkit-align-self: flex-start;
                -ms-flex-item-align: start;
                    align-self: flex-start;
        }

        .flex-self-end {
            -webkit-align-self: flex-end;
                -ms-flex-item-align: end;
                    align-self: flex-end;
        }

        .flex-self-center {
            -webkit-align-self: center;
                -ms-flex-item-align: center;
                        -ms-grid-row-align: center;
                    align-self: center;
        }

        .flex-self-baseline {
            -webkit-align-self: baseline;
                -ms-flex-item-align: baseline;
                    align-self: baseline;
        }

        .flex-self-stretch {
            -webkit-align-self: stretch;
                -ms-flex-item-align: stretch;
                        -ms-grid-row-align: stretch;
                    align-self: stretch;
        }

        .flex-grow-0 {
            -webkit-box-flex: 0;
            -webkit-flex-grow: 0;
            -moz-box-flex: 0;
                -ms-flex-positive: 0;
                    flex-grow: 0;
        }

        .flex-grow-1 {
            -webkit-box-flex: 1;
            -webkit-flex-grow: 1;
            -moz-box-flex: 1;
                -ms-flex-positive: 1;
                    flex-grow: 1;
        }

        .flex-shrink-0 {
            -webkit-flex-shrink: 0;
                -ms-flex-negative: 0;
                    flex-shrink: 0;
        }

        .flex-shrink-1 {
            -webkit-flex-shrink: 1;
                -ms-flex-negative: 1;
                    flex-shrink: 1;
        }

        .flex-basis-auto {
            -webkit-flex-basis: auto;
                -ms-flex-preferred-size: auto;
                    flex-basis: auto;
        }

        .flex-basis-0 {
            -webkit-flex-basis: 0;
                -ms-flex-preferred-size: 0;
                    flex-basis: 0;
        }

        .flex-basis-25 {
            -webkit-flex-basis: 25%;
                -ms-flex-preferred-size: 25%;
                    flex-basis: 25%;
        }

        .flex-basis-50 {
            -webkit-flex-basis: 50%;
                -ms-flex-preferred-size: 50%;
                    flex-basis: 50%;
        }

        .flex-basis-75 {
            -webkit-flex-basis: 75%;
                -ms-flex-preferred-size: 75%;
                    flex-basis: 75%;
        }

        .flex-basis-100 {
            -webkit-flex-basis: 100%;
                -ms-flex-preferred-size: 100%;
                    flex-basis: 100%;
        }

        .flex-order-0 {
            -webkit-box-ordinal-group: 1;
            -webkit-order: 0;
            -moz-box-ordinal-group: 1;
                -ms-flex-order: 0;
                    order: 0;
        }

        .flex-order-1 {
            -webkit-box-ordinal-group: 2;
            -webkit-order: 1;
            -moz-box-ordinal-group: 2;
                -ms-flex-order: 1;
                    order: 1;
        }

        .flex-order-2 {
            -webkit-box-ordinal-group: 3;
            -webkit-order: 2;
            -moz-box-ordinal-group: 3;
                -ms-flex-order: 2;
                    order: 2;
        }

        .flex-order-3 {
            -webkit-box-ordinal-group: 4;
            -webkit-order: 3;
            -moz-box-ordinal-group: 4;
                -ms-flex-order: 3;
                    order: 3;
        }

        .flex-order-4 {
            -webkit-box-ordinal-group: 5;
            -webkit-order: 4;
            -moz-box-ordinal-group: 5;
                -ms-flex-order: 4;
                    order: 4;
        }

        .flex-order-5 {
            -webkit-box-ordinal-group: 6;
            -webkit-order: 5;
            -moz-box-ordinal-group: 6;
                -ms-flex-order: 5;
                    order: 5;
        }

        .flex-order-last {
            -webkit-box-ordinal-group: 1000;
            -webkit-order: 999;
            -moz-box-ordinal-group: 1000;
                -ms-flex-order: 999;
                    order: 999;
        }

    </style>
</head>
<body>
    <div class="inline-flex">
        <div class="">111</div>
        <div class=""> 222</div>
    </div>
</body>
</html>