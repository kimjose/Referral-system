<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colored Sidebar</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS for Sidebar -->
    <style>
        /* Main sidebar styles */
        .sidebar {
            background-color: #2c3e50; /* Set sidebar background color */
            padding: 15px;
            height: 100vh;
            color: #ecf0f1; /* Text color for the sidebar */
        }

        /* Sidebar links */
        .sidebar-nav .nav-item a {
            color: #ecf0f1; /* Text color */
            padding: 10px;
            text-decoration: none;
            display: flex;
            align-items: center;
            border-radius: 5px;
        }

        .sidebar-nav .nav-item a:hover {
            background-color: #34495e; /* Hover effect background color */
            color: #ffffff; /* Text color on hover */
        }

        /* Active sidebar links */
        .sidebar-nav .nav-item a.active {
            background-color: #2980b9; /* Active state background color */
            color: #ffffff; /* Active text color */
        }

        /* Collapse arrow color */
        .sidebar-nav .nav-item .bi-chevron-down {
            color: #ecf0f1;
        }

        /* Sidebar collapse content */
        .nav-content a {
            padding-left: 30px;
            color: #bdc3c7;
        }

        .nav-content a:hover {
            background-color: #34495e;
            color: #ffffff;
        }

        /* Sidebar icons */
        .sidebar-nav .nav-item a i {
            margin-right: 10px;
        }
    </style>
</head>
<body>

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link active" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#registration-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-person-plus"></i><span>Registration</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="registration-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('patients.addPatient') }}">
                        <i class="bi bi-circle"></i><span>Register Patient</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('triages.addTriage') }}">
                        <i class="bi bi-circle"></i><span>Triage</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('phq9.addAssessment') }}">
                        <i class="bi bi-circle"></i><span>PHQ9 Assessment</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('gad7.addGad7') }}">
                        <i class="bi bi-circle"></i><span>GAD 7 Assessment</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('ptsd5.addPtsd5') }}">
                        <i class="bi bi-circle"></i><span>PTSD-5</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Registration Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#verification-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-check2-circle"></i><span>Verification</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="verification-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('verification.patient') }}">
                        <i class="bi bi-circle"></i><span>Verify Patient</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('verification.referral') }}">
                        <i class="bi bi-circle"></i><span>Verify Referral</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Verification Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#incoming-referals-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-arrow-down-left-circle"></i><span>Referrals</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="incoming-referals-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('referrals.worklist') }}">
                        <i class="bi bi-circle"></i><span>Refer patient</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('referrals.incoming') }}">
                        <i class="bi bi-circle"></i><span>Incoming Referrals</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('referral.outgoing') }}">
                        <i class="bi bi-circle"></i><span>Outgoing Referrals</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Referrals Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#reports-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-graph-up"></i><span>Reports</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="reports-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('admin.dashboard.charts') }}">
                        <i class="bi bi-circle"></i><span>Visualizations</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Reports Nav -->

        <li class="nav-heading">Settings</li>

        @can('view users')
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#user-management-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-people"></i><span>User Management</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="user-management-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                @can('view users')
                <li>
                    <a href="{{ route('user-management.index') }}">
                        <i class="bi bi-circle"></i><span>Users</span>
                    </a>
                </li>
                @endcan
                @can('view roles')
                <li>
                    <a href="{{ route('role-management.index') }}">
                        <i class="bi bi-circle"></i><span>Roles</span>
                    </a>
                </li>
                @endcan
                <li>
                    <a href="{{ route('groups.index') }}">
                        <i class="bi bi-circle"></i><span>Groups</span>
                    </a>
                </li>
            </ul>
        </li><!-- End User Management Nav -->
        @endcan

    </ul>

</aside><!-- End Sidebar-->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
