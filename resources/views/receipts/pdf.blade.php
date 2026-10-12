<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Receipt - {{ $payment->receipt_number }}</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            background: #ffffff;
            font-size: 12px;
            line-height: 1.5;
            padding: 30px;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .logo-title {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
        }
        .logo-subtitle {
            font-size: 9px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 2px;
        }
        .receipt-badge {
            background-color: #ecfdf5;
            border: 1px solid #6ee7b7;
            color: #065f46;
            font-size: 10px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 20px;
            text-transform: uppercase;
            display: inline-block;
        }
        .receipt-title {
            text-align: center;
            margin: 15px 0 20px 0;
        }
        .receipt-title h2 {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .receipt-title p {
            font-size: 10px;
            color: #64748b;
            margin-top: 2px;
        }
        .meta-box {
            width: 100%;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
        }
        .meta-table {
            width: 100%;
        }
        .meta-table td {
            width: 25%;
            vertical-align: top;
        }
        .meta-label {
            font-size: 9px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            display: block;
            margin-bottom: 2px;
        }
        .meta-value {
            font-size: 11px;
            font-weight: 700;
            color: #0f172a;
        }
        .two-col-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .two-col-table td {
            width: 50%;
            vertical-align: top;
        }
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 14px;
            background: #ffffff;
        }
        .card-header {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }
        .customer-card .card-header { color: #2563eb; }
        .lawyer-card .card-header { color: #d97706; }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            overflow: hidden;
        }
        .items-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 10px 12px;
            text-align: left;
        }
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
        }
        .items-table tfoot td {
            background-color: #f8fafc;
            border-top: 2px solid #cbd5e1;
            padding: 12px;
            font-weight: 800;
        }
        .audit-box {
            width: 100%;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 25px;
        }
        .audit-table {
            width: 100%;
        }
        .audit-table td {
            width: 50%;
            vertical-align: top;
            font-size: 10px;
        }
        .footer {
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td style="width: 60%; vertical-align: middle;">
                <div class="logo-title">JURIS SENTINEL</div>
                <div class="logo-subtitle">LAWYER APPOINTMENT PORTAL</div>
            </td>
            <td style="width: 40%; text-align: right; vertical-align: middle;">
                <div class="receipt-badge">Payment Verified</div>
                <div style="font-size: 10px; color: #64748b; margin-top: 4px;">
                    Receipt #: <strong>{{ $payment->receipt_number }}</strong>
                </div>
            </td>
        </tr>
    </table>

    <!-- Receipt Title -->
    <div class="receipt-title">
        <h2>Consultation Payment Receipt</h2>
        <p>System-generated and officially reconciled payment acknowledgment</p>
    </div>

    <!-- Meta Details Box -->
    <div class="meta-box">
        <table class="meta-table">
            <tr>
                <td>
                    <span class="meta-label">Receipt Number</span>
                    <span class="meta-value" style="color: #2563eb;">{{ $payment->receipt_number }}</span>
                </td>
                <td>
                    <span class="meta-label">Payment Date</span>
                    <span class="meta-value">{{ date('M j, Y', strtotime($payment->payment_date)) }}</span>
                </td>
                <td>
                    <span class="meta-label">Payment Method</span>
                    <span class="meta-value">{{ $payment->payment_method_name }}</span>
                </td>
                <td>
                    <span class="meta-label">Transaction ID (TID)</span>
                    <span class="meta-value">{{ $payment->transaction_id }}</span>
                </td>
            </tr>
        </table>
    </div>

    <!-- Two-column Customer & Lawyer Information -->
    <table class="two-col-table">
        <tr>
            <td style="padding-right: 10px;">
                <div class="card customer-card">
                    <div class="card-header">Customer Information</div>
                    <div style="font-size: 12px; font-weight: 700; color: #0f172a;">{{ $payment->customer->name }}</div>
                    <div style="color: #475569; font-size: 11px;">{{ $payment->customer->email }}</div>
                    <div style="color: #64748b; font-size: 10px;">Phone: {{ $payment->customer->phone ?? 'N/A' }}</div>
                </div>
            </td>
            <td style="padding-left: 10px;">
                <div class="card lawyer-card">
                    <div class="card-header">Attorney Information</div>
                    <div style="font-size: 12px; font-weight: 700; color: #0f172a;">{{ $payment->appointment->lawyer->name ?? 'Lawyer' }}</div>
                    <div style="color: #475569; font-size: 11px;">{{ $payment->appointment->lawyer->email ?? '' }}</div>
                    <div style="color: #64748b; font-size: 10px;">
                        {{ $payment->appointment->lawyer->lawyerProfile->location ?? 'City' }} &bull; License: {{ $payment->appointment->lawyer->lawyerProfile->license_number ?? 'N/A' }}
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Line Item Breakdown -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 40%;">Consultation Item</th>
                <th style="width: 30%;">Scheduled Slot</th>
                <th style="width: 15%;">Format</th>
                <th style="width: 15%; text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div style="font-weight: 700; color: #0f172a;">{{ $payment->appointment->service->name ?? 'General Legal Counsel' }}</div>
                    <div style="font-size: 10px; color: #64748b;">Appointment Code: <strong>{{ $payment->appointment->appointment_number }}</strong></div>
                </td>
                <td>
                    <div style="font-weight: 700;">{{ date('D, M j, Y', strtotime($payment->appointment->appointment_date)) }}</div>
                    <div style="font-size: 10px; color: #64748b;">{{ date('g:i A', strtotime($payment->appointment->start_time)) }} - {{ date('g:i A', strtotime($payment->appointment->end_time)) }}</div>
                </td>
                <td>
                    {{ $payment->appointment->meeting_place }}
                </td>
                <td style="text-align: right; font-weight: 700; font-size: 12px;">
                    {{ $payment->currency }}{{ number_format($payment->amount, 2) }}
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right; text-transform: uppercase; font-size: 10px; color: #475569;">Total Paid Amount:</td>
                <td style="text-align: right; font-size: 14px; font-weight: 800; color: #0f172a;">
                    {{ $payment->currency }}{{ number_format($payment->amount, 2) }}
                </td>
            </tr>
        </tfoot>
    </table>

    <!-- Verification Details -->
    <div class="audit-box">
        <table class="audit-table">
            <tr>
                <td>
                    <span class="meta-label">Verification Audit</span>
                    <div style="color: #0f172a; font-weight: 700;">Verified By: {{ $payment->verifier->name ?? 'System Administrator' }}</div>
                    <div style="color: #64748b;">Timestamp: {{ $payment->verified_at ? $payment->verified_at->format('M j, Y g:i A') : date('M j, Y') }}</div>
                </td>
                <td style="text-align: right;">
                    <span class="meta-label">Payer Account</span>
                    <div style="color: #0f172a; font-weight: 700;">{{ $payment->sender_account }} ({{ $payment->sender_name }})</div>
                    <div style="color: #059669; font-weight: 700;">STATUS: VERIFIED & CONFIRMED</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p style="font-weight: 600; color: #64748b;">This is a system-generated payment receipt.</p>
        <p style="margin-top: 3px;">Juris Sentinel &bull; Secure Legal Consultation Booking Portal</p>
    </div>

</body>
</html>
