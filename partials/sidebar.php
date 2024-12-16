<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <a href="dashboard"><img src="assets/images/logo/TECHNO LOGO.png" alt="Logo"
                            style="width: 235px; height: auto;"></a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

                <li class="sidebar-item <?php if ($active == "dashboard") {
                    echo "active";
                } ?>">
                    <a href="dashboard" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-title">Components</li>

                <li class="sidebar-item <?php if ($active == "users") {
                    echo "active";
                } ?>">
                    <a href="users" class='sidebar-link'>
                        <i class="bi bi-bookmark-check-fill"></i>
                        <span>Users</span>
                    </a>
                </li>

                <li class="sidebar-item <?php if ($active == "appointments") {
                    echo "active";
                } ?>">
                    <a href="appointments" class='sidebar-link'>
                        <i class="bi bi-info-square-fill"></i>
                        <span>Appointments</span>
                    </a>
                </li>

                <li class="sidebar-item <?php if ($active == "feedbacks") {
                    echo "active";
                } ?>">
                    <a href="feedbacks" class='sidebar-link'>
                        <i class="bi bi-info-square-fill"></i>
                        <span>Feedbacks</span>
                    </a>
                </li>

                <li class="sidebar-item <?php if ($active == "logout") {
                    echo "active";
                } ?>">
                    <a href="#" class='sidebar-link' data-bs-toggle="modal" data-bs-target="#exampleModalCenter">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </a>
                </li>

            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Confirm Logout
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <p>
                    Are you sure you want to logout?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">No</span>
                </button>
                <a href="logout" class="btn btn-primary ml-1">
                    <i class="bx bx-check d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Yes</span>
                </a>
            </div>
        </div>
    </div>
</div>