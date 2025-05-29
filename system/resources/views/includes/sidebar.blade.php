<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? '' : 'collapsed' }}"
                href="{{ route('admin.dashboard') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->

        {{-- Registration --}}
        @php
            $registrationActive = request()->routeIs('patients.*', 'triages.*', 'phq9.*', 'gad7.*', 'ptsd5.*');
        @endphp
        <li class="nav-item">
            <a class="nav-link {{ $registrationActive ? '' : 'collapsed' }}" data-bs-target="#registration-nav"
                data-bs-toggle="collapse" href="#">
                <i class="bi bi-person-plus"></i><span>Registration</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="registration-nav" class="nav-content collapse {{ $registrationActive ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('patients.addPatient') }}"
                        class="{{ request()->routeIs('patients.*') ? 'active' : '' }}">
                        <i class="bi bi-person-add"></i><span>Register Patient</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('triages.addTriage') }}"
                        class="{{ request()->routeIs('triages.*') ? 'active' : '' }}">
                        <i class="bi bi-card-checklist"></i><span>Triage</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('phq9.addAssessment') }}"
                        class="{{ request()->routeIs('phq9.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-medical"></i><span>PHQ9 Assessment</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('gad7.addGad7') }}" class="{{ request()->routeIs('gad7.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-medical"></i><span>GAD 7 Assessment</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('ptsd5.addPtsd5') }}" class="{{ request()->routeIs('ptsd5.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-medical"></i><span>PTSD-5</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Verification --}}
        @php
            $verificationActive = request()->routeIs('verify.patient', 'verify.referral');
        @endphp
        <li class="nav-item">
            <a class="nav-link {{ $verificationActive ? '' : 'collapsed' }}" data-bs-target="#verification-nav"
                data-bs-toggle="collapse" href="#">
                <i class="bi bi-check2-circle"></i><span>Verification</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="verification-nav" class="nav-content collapse {{ $verificationActive ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="#" class="{{ request()->routeIs('verify.patient') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Verify Patient</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="{{ request()->routeIs('verify.referral') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Verify Referral</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Referrals --}}
        @php
            $referralsActive = request()->routeIs('referrals.*', 'referral.*');
        @endphp
        <li class="nav-item">
            <a class="nav-link {{ $referralsActive ? '' : 'collapsed' }}" data-bs-target="#incoming-referals-nav"
                data-bs-toggle="collapse" href="#">
                <i class="bi bi-arrow-down-left-circle"></i><span>Referrals</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="incoming-referals-nav" class="nav-content collapse {{ $referralsActive ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('referrals.worklist') }}"
                        class="{{ request()->routeIs('referrals.worklist') ? 'active' : '' }}">
                        <i class="bi bi-people"></i><span>All Patients</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('referrals.incoming') }}+"
                        class="{{ request()->routeIs('referrals.incoming') ? 'active' : '' }}">
                        <i class="bi bi-person-down"></i><span>Incoming Referrals</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('referral.outgoing') }}"
                        class="{{ request()->routeIs('referral.outgoing') ? 'active' : '' }}">
                        <i class="bi bi-person-up"></i><span>Outgoing Referrals</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Reports --}}
        @php
            $reportsActive = request()->routeIs('admin.dashboard.charts');
        @endphp
        <li class="nav-item">
            <a class="nav-link {{ $reportsActive ? '' : 'collapsed' }}" data-bs-target="#reports-nav"
                data-bs-toggle="collapse" href="#">
                <i class="bi bi-graph-up"></i><span>Reports</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="reports-nav" class="nav-content collapse {{ $reportsActive ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('admin.dashboard.charts') }}"
                        class="{{ request()->routeIs('admin.dashboard.charts') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart-line"></i><span>Visualizations</span>
                    </a>
                </li>
            </ul>
        </li>

    </ul>

</aside><!-- End Sidebar -->