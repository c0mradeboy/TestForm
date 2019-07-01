<?php
/*
Template Name: Обратная связь
*/
?>


<?php


if(isset($_POST['submitted'])) {
    if(trim($_POST['contactName']) === '') {
        $nameError = '<br>Пожалуйста, введите ваше имя.';
        $hasError = true;
    } else {
        $name = trim($_POST['contactName']);
    }
	
    if(trim($_POST['contactLastName']) === '') {
        $lastnameError = '<br>Пожалуйста, введите вашу фамилию.';
        $hasError = true;
    } else {
        $lastname = trim($_POST['contactLastName']);
    }

    if(trim($_POST['email']) === '')  {
        $emailError = '<br>Пожалуйста, введите адрес вашей электронной почты.';
        $hasError = true;
    } else if (!preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", trim($_POST['email']))) {
        $emailError = 'Адрес электронной почты некорректный.';
        $hasError = true;
    } else {
        $email = trim($_POST['email']);
    }

    if(trim($_POST['subject']) === '') {
        $subject = 'Сообщение с сайта';
    } else {
        $subject = stripslashes(trim($_POST['subject']));
    }

    if(trim($_POST['comments']) === '') {
        $commentError = '<br>Пожалуйста, введите ваше сообщение.';
        $hasError = true;
    } else {
        $comments = stripslashes(trim($_POST['comments']));
    }

    if(!isset($hasError)) {
        $emailTo = get_option('tz_email');
        if (!isset($emailTo) || ($emailTo == '') ){
            $emailTo = get_option('admin_email');
        }
        $body = "Имя: $name \n\nФамилия: $lastname \n\nСообщение: \n$comments \n\nEmail: $email";
        $headers = 'From: '.$name.' <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $email;

        wp_mail($emailTo, $subject, $body, $headers);
        $emailSent = true;
        unset($_POST);
    }

} ?>

<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Clean_Commerce
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'template-parts/content', 'page' ); ?>
				
					<?php if(isset($emailSent) && $emailSent == true) { ?>
						<div class="thanks">
							<p>Спасибо за ваше письмо. Я постараюсь, как можно скорее на него ответить. <?php print "Ваш E-Mail - $email"?></p>
						</div>
					<?php } else { ?>
						<?php if(isset($hasError)) { ?>
							<p class="error">Извините, но отправить письмо не удалось. Возможно вы допустили ошибки при заполнении формы.<p>
						<?php } ?>
					<?php } ?>
							
							<form action="<?php the_permalink(); ?>" id="contactForm" method="post">
								<p>Ваше имя (обязательно):<br />
									<input type="text" name="contactName" id="contactName" class="required" value="<?php if(isset($_POST['contactName'])) echo $_POST['contactName'];?>" />
									<?php if(isset($nameError) && $nameError != '') { ?>
										<span class="error"><?=$nameError;?></span>
									<?php } ?>
								</p>
								
								<p>Ваша фамилия (обязательно):<br />
									<input type="text" name="contactLastName" id="contactLastName" class="required" value="<?php if(isset($_POST['contactLastName'])) echo $_POST['contactLastName'];?>" />
									<?php if(isset($lastnameError) && $lastnameError != '') { ?>
										<span class="error"><?=$lastnameError;?></span>
									<?php } ?>
								</p>

								<p>Тема:<br />
									<input type="text" name="subject" id="subject" value="<?php if(isset($_POST['subject']))  echo $_POST['subject'];?>" />
								</p>

								<p>Сообщение (обязательно):<br />
									<textarea name="comments" id="commentsText" rows="20" cols="30" class="required"><?php if(isset($_POST['comments'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['comments']); } else { echo $_POST['comments']; } } ?></textarea>
									<?php if(isset($commentError) && $commentError != '') { ?>
										<span class="error"><?=$commentError;?></span>
									<?php } ?>
								</p>

								<p>Ваш E-Mail (обязательно):<br />
									<input type="text" name="email" id="email" class="required email" value="<?php if(isset($_POST['email']))  echo $_POST['email'];?>" />
									<?php if(isset($emailError) && $emailError != '') { ?>
										<span class="error"><?=$emailError;?></span>
									<?php } ?>
								</p>
								<input type="submit" class="button" value="Отправить сообщение"/>
								<input type="hidden" name="submitted" id="submitted" value="true" />
							</form>
							
				<?php
					// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
					endif;
				?>

			<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
	/**
	 * Hook - clean_commerce_action_sidebar.
	 *
	 * @hooked: clean_commerce_add_sidebar - 10
	 */
	do_action( 'clean_commerce_action_sidebar' );
?>

<?php get_footer(); ?>
