<?php
/**
 * Шаблон: Преимущества
 * @var $args
 * ОЖИДАЕМЫЕ ДАННЫЕ
 * $args['title']
 * $args['advLoop']:
 *  - title
 *  - text
 *  - icon (название иконки для спрайта)
 */

$advTitle = $args['title'];
$advLoop  = $args['advLoop'];
?>


<?php if ( is_array( $advLoop ) && ! empty( $advLoop ) ) { ?>
  <section class="adv animate section">
    <div class="adv__container container">
		<?php if ( ! empty( $advTitle ) ) { ?>
          <h2 class="adv__title title"><?php echo $advTitle; ?></h2>
		<?php } ?>
      <ul class="adv__list">
		  <?php foreach ( $advLoop as $row ) {
			  $rowTitle = $row['title'];
			  $rowText  = $row['text'];
			  $rowIcon  = $row['icon'];
			  ?>
            <li class="adv__item wow animate__animated animate__slideinup">
				<?php if ( ! empty( $rowIcon ) ) { ?>
                  <span class="adv__icon adv__icon--innovation">
                <svg width="100" height="100" aria-hidden="true" focusable="false">
                  <use
                    xlink:href="<?php echo get_template_directory_uri(); ?>/assets/images/sprite.svg#<?php echo $rowIcon; ?>"></use>
                </svg>
              </span>
				<?php } ?>
				<?php if ( ! empty( $rowTitle ) ) { ?>
                  <h3 class="adv__subtitle title-sub"><?php echo $rowTitle; ?></h3>
				<?php } ?>
				<?php if ( ! empty( $rowText ) ) { ?>
                  <p class="adv__text"><?php echo $rowText; ?></p>
				<?php } ?>
            </li>

		  <?php } ?>
      </ul>
    </div>
  </section>
<?php } ?>
