<?php
/**
 * @var \App\View\AppView $this
 * @var array $student
 * @var array $application
 * @var string $referenceNumber
 * @var string $letterDate
 */

function formatTalentRouteLetterDate(?string $date): string
{
    if (!$date) {
        return 'To be confirmed';
    }

    $timestamp = strtotime($date);

    if ($timestamp === false) {
        return $date;
    }

    return date('d F Y', $timestamp);
}

$companyName = trim(
    (string)($application['display_company'] ?? '')
);

if ($companyName === '') {
    $companyName = 'Company';
}

$startDate = formatTalentRouteLetterDate(
    $application['start_date'] ?? null
);

$endDate = formatTalentRouteLetterDate(
    $application['end_date'] ?? null
);

$addressLine1 = trim(
    (string)($application['address_line1'] ?? '')
);

$addressLine2 = trim(
    (string)($application['address_line2'] ?? '')
);

$postcode = trim(
    (string)($application['postcode'] ?? '')
);

$city = trim(
    (string)($application['city'] ?? '')
);

$state = trim(
    (string)($application['state'] ?? '')
);

$postcodeCity = trim(
    $postcode . ' ' . $city
);

$companyAddressLines = array_values(
    array_filter([
        $addressLine1,
        $addressLine2,
        $postcodeCity,
        $state,
    ])
);

$remarks = trim(
    (string)($application['remarks'] ?? '')
);

$generatedDate = date('d F Y, h:i A');

$status = strtolower(trim((string)($application['status'] ?? '')));
$isRejected = $status === 'rejected';
$letterTitle = $isRejected
    ? 'Internship Application Rejection Letter'
    : 'Internship Application Acceptance Letter';
$statusText = $isRejected ? 'Rejected' : 'Approved';
$statusClass = $isRejected ? 'status-rejected' : 'status-approved';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>
        Internship Acceptance Letter
    </title>

    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: #e9eef5;
            color: #172033;
            font-family:
                Arial,
                Helvetica,
                sans-serif;
        }

        body {
            padding: 24px;
        }

        .toolbar {
            width: 210mm;
            max-width: 100%;
            margin: 0 auto 14px;
            padding: 11px 13px;
            border-radius: 12px;
            background: #ffffff;
            box-shadow:
                0 6px 20px
                rgba(15, 23, 42, 0.12);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .toolbar-title {
            min-width: 0;
        }

        .toolbar-title strong {
            display: block;
            font-size: 14px;
            color: #172033;
        }

        .toolbar-title span {
            display: block;
            margin-top: 2px;
            font-size: 11px;
            color: #758198;
        }

        .toolbar-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-end;
            gap: 8px;
        }

        .toolbar-button {
            border: 0;
            border-radius: 8px;
            padding: 10px 14px;
            background: #6d28d9;
            color: #ffffff;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            white-space: nowrap;
        }

        .toolbar-button:hover {
            background: #5b21b6;
        }

        .toolbar-button.secondary {
            border: 1px solid #d7deea;
            background: #ffffff;
            color: #344158;
        }

        .toolbar-button.secondary:hover {
            background: #f3f5f9;
        }

        .toolbar-button.danger {
            background: #dc2626;
        }

        .toolbar-button.danger:hover {
            background: #b91c1c;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            max-width: 100%;
            margin: 0 auto;
            padding: 15mm 16mm 14mm;
            background: #ffffff;
            box-shadow:
                0 12px 35px
                rgba(15, 23, 42, 0.14);
        }

        .brand-header {
            text-align: center;
        }

        .brand-logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .brand-icon {
            width: 30px;
            height: 30px;
            border-radius: 9px;
            background:
                linear-gradient(
                    135deg,
                    #7c3aed,
                    #2563eb
                );
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 900;
        }

        .brand-title {
            margin: 0;
            color: #6d28d9;
            font-size: 29px;
            font-weight: 900;
            letter-spacing: -0.5px;
        }

        .brand-subtitle {
            margin: 5px 0 0;
            color: #536078;
            font-size: 12.5px;
        }

        .header-line {
            height: 3px;
            margin-top: 16px;
            background:
                linear-gradient(
                    90deg,
                    #7c3aed,
                    #2563eb
                );
        }

        .letter-meta {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            gap: 24px;
            font-size: 12.5px;
        }

        .meta-block {
            line-height: 1.7;
        }

        .meta-right {
            text-align: right;
        }

        .meta-label {
            font-weight: 800;
            color: #172033;
        }

        .letter-title {
            margin: 24px 0 22px;
            text-align: center;
            font-size: 19px;
            font-weight: 900;
            color: #172033;
            text-transform: uppercase;
            text-decoration: underline;
            text-underline-offset: 5px;
        }

        .recipient {
            margin-bottom: 19px;
            font-size: 12.8px;
            line-height: 1.55;
        }

        .recipient-label {
            margin-bottom: 2px;
            color: #536078;
        }

        .recipient-name {
            font-weight: 800;
            color: #172033;
        }

        .body-text {
            margin: 0 0 13px;
            font-size: 12.8px;
            line-height: 1.62;
            text-align: justify;
        }

        .student-table {
            width: 100%;
            margin: 17px 0;
            border-collapse: collapse;
            font-size: 12.3px;
        }

        .student-table td {
            border: 1px solid #d7deea;
            padding: 8px 10px;
            vertical-align: top;
        }

        .student-table td:first-child {
            width: 31%;
            background: #f5f7fb;
            font-weight: 800;
            color: #344158;
        }

        .student-table td:last-child {
            font-weight: 600;
            color: #172033;
        }

        .status-approved {
            display: inline-block;
            padding: 4px 9px;
            border-radius: 999px;
            background: #dcfce7;
            color: #15803d;
            font-size: 11px;
            font-weight: 800;
        }

        .remarks-box {
            margin: 15px 0;
            padding: 11px 13px;
            border-left: 4px solid #6d28d9;
            border-radius: 4px;
            background: #f7f4ff;
            font-size: 12.3px;
            line-height: 1.5;
        }

        .remarks-title {
            margin-bottom: 4px;
            font-weight: 800;
            color: #5b21b6;
        }

        .signature-section {
            margin-top: 27px;
            display: flex;
            justify-content: space-between;
            gap: 38px;
        }

        .signature-block {
            width: 48%;
            font-size: 12px;
            line-height: 1.45;
        }

        .signature-space {
            height: 36px;
        }

        .signature-line {
            width: 210px;
            max-width: 100%;
            margin-bottom: 5px;
            border-top: 1px solid #172033;
        }

        .signature-name {
            font-weight: 800;
            color: #172033;
        }

        .signature-role {
            font-size: 11px;
            color: #536078;
        }

        .footer {
            margin-top: 28px;
            padding-top: 9px;
            border-top: 1px solid #d7deea;
            text-align: center;
            color: #758198;
            font-size: 9.5px;
            line-height: 1.45;
        }

        @media print {
            @page {
                size: A4;
                margin: 0;
            }

            html,
            body {
                background: #ffffff;
            }

            body {
                padding: 0;
            }

            .no-print {
                display: none !important;
            }

            .page {
                width: 210mm;
                min-height: 297mm;
                margin: 0;
                box-shadow: none;
                page-break-after: avoid;
            }
        }

        @media screen and (max-width: 800px) {
            body {
                padding: 10px;
            }

            .toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .toolbar-actions {
                justify-content: stretch;
            }

            .toolbar-button {
                flex: 1;
            }

            .page {
                padding: 24px 20px;
            }

            .letter-meta {
                flex-direction: column;
                gap: 5px;
            }

            .meta-right {
                text-align: left;
            }

            .signature-section {
                flex-direction: column;
            }

            .signature-block {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<div class="toolbar no-print">

    <div class="toolbar-title">
        <strong>
            Internship Acceptance Letter
        </strong>

        <span>
            TalentRoute official document
        </span>
    </div>

    <div class="toolbar-actions">


        <button
            type="button"
            class="toolbar-button danger"
            onclick="closeLetterTab()"
        >
            ✕ Close Tab
        </button>

        <button
            type="button"
            class="toolbar-button"
            onclick="window.print()"
        >
            🖨 Print / Save PDF
        </button>

    </div>

</div>

<main class="page">

    <header class="brand-header">

        <div class="brand-logo">
            <span class="brand-icon">
                T
            </span>

            <h1 class="brand-title">
                TalentRoute
            </h1>
        </div>

        <p class="brand-subtitle">
            Internship Application &amp; Management System
        </p>

        <div class="header-line"></div>

    </header>

    <section class="letter-meta">

        <div class="meta-block">
            <div>
                <span class="meta-label">
                    Reference:
                </span>

                <?= h($referenceNumber) ?>
            </div>
        </div>

        <div class="meta-block meta-right">
            <div>
                <span class="meta-label">
                    Date:
                </span>

                <?= h($letterDate) ?>
            </div>
        </div>

    </section>

    <h2 class="letter-title">
        <?= h($letterTitle) ?>
    </h2>

    <section class="recipient">

        <div class="recipient-label">To:</div>

        <?php if ($isRejected): ?>
            <div class="recipient-name"><?= h($student['name']) ?></div>
            <div><?= h($student['matrix_number']) ?></div>
            <div><?= h($student['email'] ?? '') ?></div>
        <?php else: ?>
            <div class="recipient-name">Human Resource Manager</div>
            <div class="recipient-name"><?= h($companyName) ?></div>

            <?php if (empty($companyAddressLines)): ?>
                <div>Company address not provided</div>
            <?php else: ?>
                <?php foreach ($companyAddressLines as $line): ?>
                    <div><?= h($line) ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endif; ?>

    </section>

    <p class="body-text">
        <?= $isRejected ? 'Dear ' . h($student['name']) . ',' : 'Dear Sir / Madam,' ?>
    </p>

    <p class="body-text">
        <?php if ($isRejected): ?>
            We regret to inform you that your internship application to
            <strong><?= h($companyName) ?></strong> was not successful.
            We appreciate your interest and the time taken to submit your application.
        <?php else: ?>
            We are pleased to confirm that the following student has received
            approval to undertake an internship placement at
            <strong><?= h($companyName) ?></strong>.
        <?php endif; ?>
    </p>

    <table class="student-table">
        <tbody>

            <tr>
                <td>
                    Student Name
                </td>

                <td>
                    <?= h($student['name']) ?>
                </td>
            </tr>

            <tr>
                <td>
                    Matrix Number
                </td>

                <td>
                    <?= h($student['matrix_number']) ?>
                </td>
            </tr>

            <tr>
                <td>
                    Faculty
                </td>

                <td>
                    <?= h($student['faculty']) ?>
                </td>
            </tr>

            <tr>
                <td>
                    Course
                </td>

                <td>
                    <?= h($student['course']) ?>
                </td>
            </tr>

            <tr>
                <td>
                    Semester
                </td>

                <td>
                    Semester
                    <?= h(
                        (string)$student['semester']
                    ) ?>
                </td>
            </tr>

            <tr>
                <td>
                    Internship Company
                </td>

                <td>
                    <?= h($companyName) ?>
                </td>
            </tr>

            <tr>
                <td>
                    Internship Period
                </td>

                <td>
                    <?= h($startDate) ?>
                    until
                    <?= h($endDate) ?>
                </td>
            </tr>

            <tr>
                <td>
                    Application Status
                </td>

                <td>
                    <span class="<?= h($statusClass) ?>">
                        <?= h($statusText) ?>
                    </span>
                </td>
            </tr>

        </tbody>
    </table>

    <p class="body-text">
        <?php if ($isRejected): ?>
            This decision applies only to the application shown in this letter.
            You may continue searching and submit a new application to another
            available company through TalentRoute.
        <?php else: ?>
            The student is expected to comply with the organisation's policies,
            working procedures, professional standards and internship requirements
            throughout the placement period.
        <?php endif; ?>
    </p>

    <?php if ($remarks !== ''): ?>
        <div class="remarks-box">

            <div class="remarks-title">
                Remarks
            </div>

            <div>
                <?= nl2br(h($remarks)) ?>
            </div>

        </div>
    <?php endif; ?>

    <p class="body-text">
        <?php if ($isRejected): ?>
            We wish you success in securing a suitable internship placement.
        <?php else: ?>
            We appreciate the cooperation and support provided by your organisation
            in offering this internship opportunity to the student.
        <?php endif; ?>
    </p>

    <p class="body-text">
        Thank you.
    </p>

    <section class="signature-section">

        <div class="signature-block">

            <div>
                Yours faithfully,
            </div>

            <div class="signature-space"></div>

            <div class="signature-line"></div>

            <div class="signature-name">
                Internship Coordinator
            </div>

            <div class="signature-role">
                TalentRoute Internship Management Unit
            </div>

        </div>

        <div class="signature-block">

            <div>
                Acknowledged by,
            </div>

            <div class="signature-space"></div>

            <div class="signature-line"></div>

            <div class="signature-name">
                Human Resource Manager
            </div>

            <div class="signature-role">
                <?= h($companyName) ?>
            </div>

        </div>

    </section>

    <footer class="footer">

        This letter was generated electronically through
        the TalentRoute Internship Application &amp;
        Management System.

        <br>

        Reference:
        <?= h($referenceNumber) ?>

        &nbsp; | &nbsp;

        Generated:
        <?= h($generatedDate) ?>

    </footer>

</main>

<script>
function closeLetterTab() {
    window.close();

    setTimeout(function () {
        if (!window.closed) {
            alert(
                'Browser tidak membenarkan tab ini ditutup secara automatik. Sila tutup tab ini secara manual.'
            );
        }
    }, 150);
}
</script>

</body>
</html>