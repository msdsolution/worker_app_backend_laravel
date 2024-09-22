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
        .thank-you {
            text-align: center;
        }
        .body-section {
            padding: 20px;
            border: 1px solid gray;
            margin-bottom: 20px;
            background-color: white;
        }
        .heading {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .sub-heading {
            color: #262626;
            margin-bottom: 3px;
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
        table th {
            font-size: 15px; /* Larger text for table headers */
        }

        table td {
            font-size: 14px; /* Smaller text for table data */
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
            f
            loat: right;
        }
        .job-status {
            text-align: right;
        }
        .status-success {
            color: green;
        }

        .status-failure {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="brand-section">
            <div class="heading">
                <h2 class="text-white" style="text-align: center;">Rata Mithuro Support Services (Pvt) Ltd <br>(PV 00286715)</h2>
            </div>
            <div class="company-details">
                <p>950/4,T.C Garden Road,</p>
                <p>Battaramulla, Colombo,</p>
                <p>Sri Lanka.</p>
                <p>+61 402 891 549</p>
                <p>info@ratamithuro.com</p>
            </div>
        </div>

        <div class="body-section">
            @if($job->status == 5)
            <div class="job-status status-success">
                    <p class="heading">Status: {{ $job->status == 5 ? 'Paid' : 'Not Paid' }}</p>
            </div>
            @else
            <div class="job-status status-failure">
                    <p class="heading">Status: {{ $job->status == 5 ? 'Paid' : 'Not Paid' }}</p>
            </div>
            @endif
            <div class="row">
                <div class="col-6">
                    <h2 class="heading">Invoice No: {{ $job->job_no ?? 'N/A' }}</h2>
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
            <h6 class="sub-heading text-right">Currency: LKR</h6>
            <h3 class="heading">Ordered Items</h3>
            <table class="table-bordered">
                <thead>
                    <tr>
                        <th>Job Description</th>
                        <th class="w-20">Service Category</th>
                        <th class="w-20">Quantity</th>
                        <th class="w-20">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $job->jobDescription }}</td>
                        <td>{{ $categoryNames .' '. $job->required_date}}</td>
                        <!-- <td>{{ $referalAmount->refferal_amount }}</td> -->
                        <td>4 hours</td>
                        <td>{{ $referalAmount->refferal_amount}}</td>
                    </tr>
                    <!-- <tr>
                        <td colspan="3" class="text-right">Sub Total</td>
                        <td>{{ $referalAmount->refferal_amount }}</td>
                    </tr> -->
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
                        <td colspan="3" class="text-right">Sub Total</td>
                        <td>{{ $grandTotal }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="body-section">
            <h4 class="sub-heading">Direct deposit details</h4>

            <p>Account name: Rata Mithuro Support Services Private Limited<br>
            Current account number: 100100012023 (LKR)<br>
            Bank code: 7162<br>
            Branch code: 010 (Battaramulla)<br>
            Swift code: NTBCLKLX</p>

            <h4 class="sub-heading">Online - Via Payment Gateway</h4>

            <h4 class="status-failure">Payment Terms: 7 days</h4>
        </div>
        <div class="thank-you">THANK YOU FOR YOUR BUSINESS</div>
        <div class="body-section">
            <p>&copy; 2024 Ratamithuro. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
