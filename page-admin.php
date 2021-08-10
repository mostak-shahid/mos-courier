<?php
global $page_list;
$page_slug = (isset($_GET['p'])) ? $_GET['p'] : 'welcome';
require_once ( plugin_dir_path( MOS_COURIER_FILE ) . 'header-courier.php' );
?>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<div class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0 text-dark"><?php echo $page_list[$page_slug] ?></h1>
						</div><!-- /.col -->
					<?php if (@$page_slug != 'dashboard') : ?>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="<?php echo home_url( '/admin/' ); ?>"><?php echo $page_list['dashboard'] ?></a></li>
								<li class="breadcrumb-item active"><?php echo $page_list[$page_slug] ?></li>
							</ol>
						</div><!-- /.col -->
					<?php endif; ?>
					</div><!-- /.row -->
				</div><!-- /.container-fluid -->
			</div>
			<!-- /.content-header -->

			<!-- Main content -->
			<div class="content">
				<div class="container-fluid">
					<?php do_action( 'courier_content', $page_slug ); ?>
				</div><!-- /.container-fluid -->
			</div>
			<!-- /.content -->
		</div>
		<!-- /.content-wrapper -->
<?php require_once ( plugin_dir_path( MOS_COURIER_FILE ) . 'footer-courier.php' ); ?>