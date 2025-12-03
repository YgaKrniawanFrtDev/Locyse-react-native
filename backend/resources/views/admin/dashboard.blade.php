@extends('layouts.admin')

@section('title', 'Dashboard - Admin')
@section('page_title', 'Dashboard')

@section('content')
    <div class="dashboard-grid">
        <section class="card card-overview">
            <div class="card-overview-header">
                <div>
                    <p class="label text-light">Attendance Overview</p>
                    <h2 class="text-white">287 Present</h2>
                </div>
                <span class="badge">This Month</span>
            </div>
            <div class="card-overview-graph">
                <div class="graph-line"></div>
                <div class="graph-dots">
                    <span></span><span></span><span class="active"></span><span></span><span></span><span></span><span></span>
                </div>
                <div class="graph-labels">
                    <span>Week 1</span><span>Week 2</span><span>Week 3</span><span>Week 4</span><span>Week 5</span><span>Week 6</span><span>Week 7</span>
                </div>
            </div>
            <div class="card-overview-footer">
                <div>
                    <p class="label">Total Employees</p>
                    <p class="value">300</p>
                    <p class="muted">December</p>
                </div>
                <div>
                    <p class="label">Present</p>
                    <p class="value">287</p>
                    <p class="muted">December</p>
                </div>
                <div>
                    <p class="label">Attendance Rate</p>
                    <p class="value">95.7%</p>
                    <p class="muted">December</p>
                </div>
            </div>
        </section>

        <section class="card card-vertical card-purple">
            <p class="label">Present Today</p>
            <h2>285</h2>
            <p class="muted">Out of 300 employees</p>
        </section>

        <section class="card card-vertical card-pink">
            <p class="label">Late Arrivals</p>
            <h2>12</h2>
            <p class="muted">Today - 04:00 PM</p>
        </section>

        <section class="card card-metric">
            <div class="metric-icon metric-purple"></div>
            <div class="metric-body">
                <h3>Present</h3>
                <p class="muted">287 employees</p>
                <div class="metric-progress">
                    <div class="metric-progress-bar" style="--progress:95%"></div>
                </div>
                <div class="metric-footer">
                    <span class="muted">Attendance Rate</span>
                    <span class="pill pill-green">95.7%</span>
                </div>
            </div>
        </section>

        <section class="card card-metric">
            <div class="metric-icon metric-indigo"></div>
            <div class="metric-body">
                <h3>Late</h3>
                <p class="muted">12 employees</p>
                <div class="metric-progress">
                    <div class="metric-progress-bar" style="--progress:4%"></div>
                </div>
                <div class="metric-footer">
                    <span class="muted">Late Rate</span>
                    <span class="pill pill-orange">4%</span>
                </div>
            </div>
        </section>

        <section class="card card-metric">
            <div class="metric-icon metric-pink"></div>
            <div class="metric-body">
                <h3>Absent</h3>
                <p class="muted">1 employee</p>
                <div class="metric-progress">
                    <div class="metric-progress-bar" style="--progress:0.3%"></div>
                </div>
                <div class="metric-footer">
                    <span class="muted">Absence Rate</span>
                    <span class="pill pill-red">0.3%</span>
                </div>
            </div>
        </section>
    </div>
@endsection
