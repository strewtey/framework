<?php // @command
?><!DOCTYPE html>
	<html lang="<?= lang(); ?>" data-animation-css="<?= isset($_GET['css']) ? $_GET['css'] : 'auto' ?>" data-animation-js="1" data-theme="<?= isset($_GET['theme']) ? $_GET['theme'] : 'auto' ?>" data-phone="<?= config('mobile') == true ? 'false' : 'true'; ?>">
		<?php /* data-theme="auto" */ ?>
		<?php /* data-theme="hour" */ ?>
		<?php /* data-theme="light" */ ?>
		<?php /* data-theme="dark" */ ?>
		<head>
			<!-- TITLE START -->
				<title><?= title(); ?></title>
			<!-- TITLE STOP -->

			<!-- ICON START -->
				<link href="<?= route('external-url', ['url' => cdn('icon/svg/icons8_physics.svg')]); ?>" rel="icon" type="image/svg">
			<!-- ICON STOP -->

			<!-- META START -->
				<?php incl('partials.meta'); ?>
			<!-- META STOP -->

			<!-- STYLESHEET START -->
				<!-- COMPONENTS FILE START -->
					<?= assetsCompnents('css'); ?>
				<!-- COMPONENTS FILE STOP -->
				<?php incl('partials.css'); ?>
				<link rel="stylesheet" type="text/css" href="<?= assets('css/excepts/layouts/@locate_css.css'); ?>">
			<!-- STYLESHEET STOP -->

			<!-- SCRIPT START -->
				<?php incl('partials.jsHead'); ?>
			<!-- SCRIPT STOP -->
				
			<!-- COMPONENTS JS FILE START -->
				<?= assetsCompnents('js'); ?>
			<!-- COMPONENTS JS FILE STOP -->
		</head>
		<body>
			<?= area('content'); ?>
			<script type="text/javascript" src="<?= assets('js/excepts/layouts/@locate_js.js'); ?>"></script>
			<?= generate('js', null, null, false); ?>
			<?php
				if(is_array(entity('js'))){
					foreach (entity('js') as $value)
						echo $value;
				}
				else echo entity('js');
			?>
		</body>
	</html>