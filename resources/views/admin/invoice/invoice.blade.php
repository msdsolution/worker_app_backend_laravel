<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RataMithuro</title>
    <style>
        body {
            background-color: #F6F6F6;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        h1, h2, h3, h4, h5, h6 {
            margin: 0;
            padding: 0;
        }
        p {
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            margin: 20px auto;
        }
        .brand-section {
            background-color: #0d1033;
            padding: 20px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .logo {
            height: 50px;
            margin-left: 20px;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
        }
        .col-6 {
            width: 48%;
        }
        .company-details {
            text-align: right;
        }
        .body-section {
            padding: 20px;
            border: 1px solid gray;
            margin-bottom: 20px;
            background-color: white;
        }
        .heading {
            font-size: 20px;
            margin-bottom: 10px;
        }
        .sub-heading {
            color: #262626;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table thead tr {
            background-color: #f2f2f2;
            border: 1px solid #111;
        }
        table td, table th {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: center;
        }
        .table-bordered {
            box-shadow: 0px 0px 5px 0.5px gray;
        }
        .text-right {
            text-align: right;
        }
        .w-20 {
            width: 20%;
        }
        .float-right {
            float: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="brand-section">
            <div>
                <h1 class="text-white">RataMithuro</h1>
            </div>
            <div class="company-details">
                <p>950/4,T.C Garden Road,</p>
                <p>Battaramulla, Colombo,</p>
                <p>Sri Lanka.</p>
                <p>+61 402 891 549</p>
            </div>
        </div>

        <div class="body-section">
            <div class="row">
                <div class="col-6">
                    <h2 class="heading">Invoice No.: {{ $job->job_no ?? 'N/A' }}</h2>
                    <p class="sub-heading">Created Date: {{ $job->created_at }}</p>
                    <p class="sub-heading">Email Address: {{ $job->Email }}</p>
                </div>
                <div class="col-6">
                    <p class="sub-heading">Full Name: {{ $job->userFirstName ?? '' }} {{ $job->userLastName ?? '' }}</p>
                    <p class="sub-heading">Address: {{ $job->Address }}</p>
                    <p class="sub-heading">Phone Number: {{ $job->Phonenumber }}</p>
                </div>
            </div>
        </div>

        <div class="body-section">
            <h3 class="heading">Ordered Items</h3>
            <table class="table-bordered">
                <thead>
                    <tr>
                        <th>Job Description</th>
                        <th class="w-20">Service Category</th>
                        <th class="w-20">Price</th>
                        <th class="w-20">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $job->jobDescription }}</td>
                        <td>{{ $categoryNames }}</td>
                        <td>{{ $referalAmount->refferal_amount }}</td>
                        <td>{{ $referalAmount->refferal_amount}}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right">Sub Total</td>
                        <td>{{ $referalAmount->refferal_amount }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-right">Is hours extended</td>
                        <td>{{ $isExtended }}</td>
                    </tr>
                    @if($job->is_extended)
                    <tr>
                        <td colspan="3" class="text-right">Extended Hours</td>
                        <td>{{ $job->extended_hrs }}</td>
                    </tr>
                    @endif
                    @if($job->is_worker_tip == 1)
                    <tr>
                        <td colspan="3" class="text-right">Worker Tip</td>
                        <td>{{ $workerTipAmount }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="3" class="text-right">Grand Total</td>
                        <td>{{ $grandTotal }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="body-section">
            <p>&copy; 2024 Ratamithuro. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
