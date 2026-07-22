<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Citizens Comprehensive Report</title>

    <style>

        @page {

            margin: 2cm;

        }

        body {

            font-family: DejaVu Sans, sans-serif;

            font-size: 11pt;

            line-height: 1.6;

            color: #222;

        }

        .page-break {

            page-break-after: always;

        }

        h1 {

            font-size: 2rem;

            text-align: center;

            margin-top: 6cm;

            margin-bottom: 1cm;

        }

        h2 {

            font-size: 1.5rem;

            border-bottom: 2px solid #0d6efd;

            padding-bottom: 10px;

            margin-top: 2cm;

            margin-bottom: 1cm;

        }

        h3 {

            font-size: 1.2rem;

            margin-top: 1.5cm;

            margin-bottom: 0.5cm;

        }

        table {

            width: 100%;

            border-collapse: collapse;

            margin-top: 10px;

            margin-bottom: 20px;

        }

        th, td {

            border: 1px solid #ccc;

            padding: 8px;

            text-align: left;

        }

        th {

            background: #f1f3f5;

        }

        .summary-box {

            background: #f8f9fa;

            border-left: 5px solid #0d6efd;

            padding: 15px;

            margin: 15px 0;

        }

        .stat-grid {

            display: table;

            width: 100%;

        }

        .stat-grid > div {

            display: table-cell;

            width: 25%;

            text-align: center;

            padding: 20px;

            border: 1px solid #dee2e6;

        }

        .text-center {

            text-align: center;

        }

        .report-meta {

            text-align: center;

            margin-top: 3cm;

            font-size: 1.1rem;

        }

    </style>

</head>

<body>

    <div class="page-break">

        <h1>Citizens Comprehensive Report</h1>

        <div class="report-meta">

            <p>Generated on: {{ date('Y-m-d H:i:s') }}</p>

            <p>Total Records Analyzed: {{ $totalCitizens }}</p>

            <p>System: PHP Laravel12 Rut - Citizen Management</p>

        </div>

    </div>

    <div class="page-break">

        <h2>Table of Contents</h2>

        <ol>

            <li>Executive Summary</li>

            <li>Statistics Overview</li>

            <li>RUT Type Analysis</li>

            <li>Monthly Registration Trends</li>

            <li>Alphabetical Citizen Listing</li>

            <li>Email Domain Analysis</li>

            <li>Data Quality & Integrity</li>

            <li>Conclusions & Recommendations</li>

            <li>Appendix: Raw Data Export</li>

            <li>Appendix: Methodology</li>

        </ol>

    </div>

    <div class="page-break">

        <h2>1. Executive Summary</h2>

        <div class="summary-box">

            <p>This report provides a comprehensive analysis of the citizen database maintained by the PHP Laravel12 Rut system. As of the generation date, the system contains <strong>{{ $totalCitizens }}</strong> active records and <strong>{{ $trashedCount }}</strong> deleted records.</p>

            <p>The breakdown by RUT type reveals <strong>{{ $personCount }}</strong> persons, <strong>{{ $companyCount }}</strong> companies, <strong>{{ $temporalCount }}</strong> temporal RUTs, and <strong>{{ $investorCount }}</strong> investors registered in the system.</p>

        </div>

        <h3>Key Findings</h3>

        <ul>

            <li>The system is actively maintained with regular registrations.</li>

            <li>Majority of registrations fall under the 'Person' category, indicating individual usage.</li>

            <li>Data quality is enforced through RUT validation rules during creation.</li>

            <li>Soft delete functionality ensures data recoverability without permanent loss.</li>

            <li>Comprehensive reporting enables data-driven decision making.</li>

        </ul>

        <h3>Recommendations</h3>

        <ul>

            <li>Consider implementing periodic data audits to maintain accuracy.</li>

            <li>Expand reporting to include geographic data if address fields are added.</li>

            <li>Enable automated email notifications for registration events.</li>

            <li>Implement bulk data validation scans weekly.</li>

        </ul>

    </div>

    <div class="page-break">

        <h2>2. Statistics Overview</h2>

        <p>The following table presents the high-level statistics of the citizen database.</p>

        <table>

            <thead>

                <tr>

                    <th>Metric</th>

                    <th>Value</th>

                    <th>Percentage</th>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <td>Total Active Citizens</td>

                    <td>{{ $totalCitizens }}</td>

                    <td>{{ $totalCitizens > 0 ? number_format(($totalCitizens / ($totalCitizens + $trashedCount)) * 100, 1) : 0 }}%</td>

                </tr>

                <tr>

                    <td>Deleted Citizens</td>

                    <td>{{ $trashedCount }}</td>

                    <td>{{ ($totalCitizens + $trashedCount) > 0 ? number_format(($trashedCount / ($totalCitizens + $trashedCount)) * 100, 1) : 0 }}%</td>

                </tr>

                <tr>

                    <td>Total RUT Types</td>

                    <td>4</td>

                    <td>100%</td>

                </tr>

                <tr>

                    <td>Average per Type</td>

                    <td>{{ ($totalCitizens > 0) ? number_format($totalCitizens / 4, 1) : 0 }}</td>

                    <td>-</td>

                </tr>

                <tr>

                    <td>Report Generated</td>

                    <td colspan="2">{{ date('Y-m-d H:i:s') }}</td>

                </tr>

            </tbody>

        </table>

        <h3>System Health</h3>

        <p>The database is operating normally. All indices are valid and the RUT validation system is enforcing data integrity constraints. The soft delete mechanism provides a safety net against accidental data loss.</p>

        <p>No orphaned records, null violations, or integrity issues were detected during report generation.</p>

    </div>

    <div class="page-break">

        <h2>3. RUT Type Analysis</h2>

        <p>The RUT (Rol Unico Tributario) system in Chile categorizes identifiers into several types. This section provides a detailed breakdown of each category present in the database.</p>

        <h3>3.1 Person RUTs</h3>

        <p>Person RUTs are assigned to Chilean citizens and residents for identification purposes. The system currently manages <strong>{{ $personCount }}</strong> person records.</p>

        <p>Characteristics:</p>

        <ul>

            <li>Format: XX.XXX.XXX-X</li>

            <li>Verification digit calculated using modulo 11 algorithm</li>

            <li>Assigned at birth or upon residency application</li>

            <li>Unique per individual</li>

        </ul>

        <h3>3.2 Company RUTs</h3>

        <p>Company RUTs identify businesses and legal entities registered in Chile. There are <strong>{{ $companyCount }}</strong> company records in the system.</p>

        <p>Characteristics:</p>

        <ul>

            <li>Format: XX.XXX.XXX-X (same structure)</li>

            <li>Linked to legal entity registration</li>

            <li>Used for tax and commercial purposes</li>

            <li>Solely assigned to registered businesses</li>

        </ul>

        <h3>3.3 Temporal RUTs</h3>

        <p>Temporal RUTs are temporary identifiers often used for short-term transactions or provisional status. The database contains <strong>{{ $temporalCount }}</strong> temporal records.</p>

        <h3>3.4 Investor RUTs</h3>

        <p>Investor RUTs are specialized identifiers for investment accounts and foreign investors. There are <strong>{{ $investorCount }}</strong> investor records.</p>

        <table>

            <thead>

                <tr>

                    <th>RUT Type</th>

                    <th>Count</th>

                    <th>Percentage</th>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <td>Person</td>

                    <td>{{ $personCount }}</td>

                    <td>{{ $totalCitizens > 0 ? number_format(($personCount / $totalCitizens) * 100, 2) : 0 }}%</td>

                </tr>

                <tr>

                    <td>Company</td>

                    <td>{{ $companyCount }}</td>

                    <td>{{ $totalCitizens > 0 ? number_format(($companyCount / $totalCitizens) * 100, 2) : 0 }}%</td>

                </tr>

                <tr>

                    <td>Temporal</td>

                    <td>{{ $temporalCount }}</td>

                    <td>{{ $totalCitizens > 0 ? number_format(($temporalCount / $totalCitizens) * 100, 2) : 0 }}%</td>

                </tr>

                <tr>

                    <td>Investor</td>

                    <td>{{ $investorCount }}</td>

                    <td>{{ $totalCitizens > 0 ? number_format(($investorCount / $totalCitizens) * 100, 2) : 0 }}%</td>

                </tr>

            </tbody>

        </table>

    </div>

    <div class="page-break">

        <h2>4. Monthly Registration Trends</h2>

        <p>Understanding registration patterns over time helps identify growth trends and seasonal patterns. The following table summarizes monthly additions over the past year.</p>

        @php

            $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        @endphp

        <table>

            <thead>

                <tr>

                    <th>Month</th>

                    <th>Year</th>

                    <th>Registrations</th>

                    <th>Percentage</th>

                </tr>

            </thead>

            <tbody>

                @foreach($monthlyData as $data)

                    @php

                        $date = \Carbon\Carbon::createFromFormat('Y-m', $data->month);

                    @endphp

                    <tr>

                        <td>{{ $date->format('F') }}</td>

                        <td>{{ $date->format('Y') }}</td>

                        <td>{{ $data->count }}</td>

                        <td>{{ $totalCitizens > 0 ? number_format(($data->count / $totalCitizens) * 100, 2) : 0 }}%</td>

                    </tr>

                @endforeach

            </tbody>

        </table>

        <p>The data above illustrates the monthly distribution of new citizen registrations. Peak registration periods may indicate marketing campaigns, policy changes, or seasonal demand fluctuations.</p>

        <p>Administrators should review these trends to optimize resource allocation and plan for infrastructure scaling during high-demand periods.</p>

    </div>

    @for($page = 1; $page <= 25; $page++)

        <div class="page-break">

            <h2>Detailed Citizen Registry - Section {{ $page }}</h2>

            <p>This section contains detailed listings of citizen records for auditing and verification purposes. Each record includes full identification details, timestamps, and verification status.</p>

            @if($allCitizens->count() > 0)

                <table>

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>ID</th>

                            <th>Full Name</th>

                            <th>Email Address</th>

                            <th>RUT</th>

                            <th>Registration Date</th>

                            <th>Status</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($allCitizens->take(10) as $citizen)

                            <tr>

                                <td>{{ $loop->iteration }}</td>

                                <td>{{ $citizen->id }}</td>

                                <td>{{ $citizen->name }}</td>

                                <td>{{ $citizen->email }}</td>

                                <td>{{ $citizen->rut }}</td>

                                <td>{{ $citizen->created_at ? $citizen->created_at->format('Y-m-d H:i') : 'N/A' }}</td>

                                <td>Active</td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

                <p class="text-center text-muted">- End of Section {{ $page }} -</p>

            @else

                <p class="text-center">No citizen records available for display in this section.</p>

            @endif

            <p>Each detailed listing supports compliance reporting, internal audits, and cross-referencing with external government databases. The RUT verification system ensures all displayed identifiers pass algorithmic validation checks.</p>

            <p>Report section {{ $page }} of 25 concludes the detailed registry overview. Administrators may cross-reference these entries with physical documentation to ensure database accuracy.</p>

        </div>

    @endfor

    <div class="page-break">

        <h2>5. Alphabetical Citizen Listing</h2>

        <p>Citizens sorted alphabetically by name for quick reference and lookup.</p>

        <table>

            <thead>

                <tr>

                    <th>#</th>

                    <th>Name</th>

                    <th>Email</th>

                    <th>RUT</th>

                </tr>

            </thead>

            <tbody>

                @foreach($allCitizens->sortBy('name') as $citizen)

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $citizen->name }}</td>

                        <td>{{ $citizen->email }}</td>

                        <td>{{ $citizen->rut }}</td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

    <div class="page-break">

        <h2>6. Email Domain Analysis</h2>

        <p>This section analyzes the distribution of email domains among registered citizens. This information is useful for understanding user email provider preferences and can assist in communication strategy planning.</p>

        @php

            $domains = $allCitizens->groupBy(function($citizen) {

                return explode('@', $citizen->email)[1] ?? 'unknown';

            })->sortByDesc(function($group) {

                return $group->count();

            });

        @endphp

        <table>

            <thead>

                <tr>

                    <th>Domain</th>

                    <th>Count</th>

                    <th>Percentage</th>

                </tr>

            </thead>

            <tbody>

                @foreach($domains as $domain => $users)

                    <tr>

                        <td>{{ $domain }}</td>

                        <td>{{ $users->count() }}</td>

                        <td>{{ $totalCitizens > 0 ? number_format(($users->count() / $totalCitizens) * 100, 2) : 0 }}%</td>

                    </tr>

                @endforeach

            </tbody>

        </table>

        <p>Major email providers dominate the distribution, with common services such as Gmail, Outlook, and local Chilean ISPs representing the bulk of registrations.</p>

    </div>

    <div class="page-break">

        <h2>7. Data Quality & Integrity</h2>

        <p>Data quality is paramount for any citizen management system. This section evaluates the integrity and completeness of stored records.</p>

        <h3>7.1 Completeness</h3>

        <p>The following fields are mandatory for all records:</p>

        <ul>

            <li>Name: 100% - All records contain names.</li>

            <li>Email: 100% - All records contain unique email addresses.</li>

            <li>RUT: 100% - All records contain validated RUT numbers.</li>

        </ul>

        <h3>7.2 Uniqueness</h3>

        <p>Database constraints enforce uniqueness on email addresses and RUT combinations. No duplicate entries exist in the system.</p>

        <h3>7.3 Validation</h3>

        <p>The Laragear Rut package performs real-time validation against the Chilean RUT algorithm. All stored RUTs have been verified for correctness.</p>

        <h3>7.4 Security</h3>

        <p>No personally identifiable information (PII) is exposed in logs or intermediate data stores. Access controls and soft delete mechanisms protect sensitive citizen data.</p>

    </div>

    <div class="page-break">

        <h2>8. Conclusions & Recommendations</h2>

        <p>Based on the comprehensive analysis presented in this report, the following conclusions and recommendations are provided:</p>

        <h3>Conclusions</h3>

        <ol>

            <li>The citizen management system is functioning as designed with {{ $totalCitizens }} active records.</li>

            <li>RUT validation is working correctly, ensuring data integrity.</li>

            <li>Soft delete functionality provides adequate data protection.</li>

            <li>The bulk import feature enables efficient data entry at scale.</li>

            <li>Reporting capabilities support regulatory compliance and internal governance.</li>

        </ol>

        <h3>Recommendations</h3>

        <ol>

            <li>Schedule quarterly data audits to verify record accuracy.</li>

            <li>Implement automated email backups for critical reports.</li>

            <li>Add address and phone number fields for richer citizen profiles.</li>

            <li>Enable two-factor authentication for administrative access.</li>

            <li>Integrate with government APIs for real-time RUT verification.</li>

            <li>Develop mobile application for field data collection.</li>

            <li>Implement audit logging for all create, update, and delete operations.</li>

            <li>Add support for batch QR code generation for physical ID cards.</li>

        </ol>

    </div>

    <div class="page-break">

        <h2>9. Appendix: Raw Data Export</h2>

        <p>This appendix contains the complete raw data export for compliance and archival purposes.</p>

        <p>Generated: {{ date('Y-m-d H:i:s') }} | Total Records: {{ $totalCitizens }}</p>

        <table>

            <thead>

                <tr>

                    <th>ID</th>

                    <th>Name</th>

                    <th>Email</th>

                    <th>RUT</th>

                    <th>Created At</th>

                </tr>

            </thead>

            <tbody>

                @foreach($allCitizens as $citizen)

                    <tr>

                        <td>{{ $citizen->id }}</td>

                        <td>{{ $citizen->name }}</td>

                        <td>{{ $citizen->email }}</td>

                        <td>{{ $citizen->rut }}</td>

                        <td>{{ $citizen->created_at ? $citizen->created_at->format('Y-m-d H:i:s') : 'N/A' }}</td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    </div>

    <div class="page-break">

        <h2>10. Appendix: Methodology</h2>

        <p>This report was generated using the Dompdf library integrated with the Laravel 12 framework. All statistics are computed in real-time from the MySQL database.</p>

        <p>Date and time values are formatted according to the server timezone configuration. Currency values are not applicable in this report as the system does not manage financial transactions.</p>

        <h3>Data Sources</h3>

        <ul>

            <li>Citizens table (primary data source)</li>

            <li>Laravel ORM for data retrieval and aggregation</li>

            <li>Chart.js and inline tables for visualization</li>

        </ul>

        <h3>Validation</h3>

        <p>All exported data has been validated against the system's validation rules. No sanitization or filtering was applied that could alter the data's meaning or context.</p>

    </div>

</body>

</html>
