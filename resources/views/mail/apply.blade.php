@extends('mail.layout')
@section('style')
    <style>
        .greetings {
            font-size: 14px;
            margin-bottom: 2px;
        }
        .table{
            margin-bottom:5px;
            overflow-y:auto;
            text-align:center;
        }
        .closer{
            font-size: 14px;
        }
        table{
            width:100%;
            border-collapse: collapse
        }
        table th{
            background-color:#273656;
            color:#fff;
            padding:10px;
            font-weight:bold;
        }
        tr{
            border:none;
        }
        td,th{
            border-left: 1px solid #273656;
            border-right: 1px solid #273656;
        }
        p{
            margin:0;
            color:#000 !important;
        }
        tfoot > td{
            background-color:#273656;
            color:#fff !important;
            padding:10px;
            font-weight:bold;
        }
    </style>
@endsection
@section('content')
    <div class="greetings">
        <p><b>Dear {{ $data['name'] }},</b></p><br>
        <p>This is your application to Practicum : </p>
    </div>
    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pilihan ke</th>
                    <th>Praktikum</th>
                    <th>Kelas</th>
                    <th>Jadwal</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                    if(!isset($data['apply']) || $data['apply'] == null):
                @endphp
                <tr>
                    <td colspan="5">No Data Found</td>
                </tr>
                @php
                    else:
                @endphp
                @foreach($data['apply'] as $app)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $app['choice'] }}</td>
                        <td>{{ $app['practicum'] }}</td>
                        <td>{{ $app['code'] }}</td>
                        <td>{{ $app['jadwal'] }}</td>
                    </tr>
                @php
                    $i++;
                @endphp
                @endforeach
                @php
                    endif;
                @endphp
            </tbody>
            <tfoot>
                <td colspan="5">This is your result of Practicum Application</td>
            </tfoot>
        </table>

    </div>
    <div class="closer">
        <p>Please contact admin responsible, if there is any wrong data!</p><br>
        <p>Contact: @chris_julius @leonick14</p>
        <p>Thank you,</p>
    </div>
@endsection