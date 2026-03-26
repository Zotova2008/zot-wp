<?php
/**
 * @var array $args
 */
?>

<section class="pets section">
  <div class="pets__container container"><h2 class="pets__title title">Продукция ZOTICO<br>подходит для всех домашних
      животных!<sup>*</sup></h2>
    <p class="pets__text">* - Внимательно читайте надписи на упаковке</p>
    <ul class="pets__list">
      <li class="pets__item pets__item--big">
        <picture>
          <source type="image/avif"
            srcset="<?php echo get_template_directory_uri(); ?>/assets/images/pets/big-dogs@2x.avif">
          <source type="image/webp"
            srcset="<?php echo get_template_directory_uri(); ?>/assets/images/pets/big-dogs@2x.webp">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/pets/big-dogs@2x.png" width="100"
            height="150" alt="Большая собака." loading="lazy">
        </picture>
        <h3 class="pets__subtitle">Большие<br>собаки</h3></li>
      <li class="pets__item pets__item--small">
        <picture>
          <source type="image/avif"
            srcset="<?php echo get_template_directory_uri(); ?>/assets/images/pets/small-dogs@2x.avif">
          <source type="image/webp"
            srcset="<?php echo get_template_directory_uri(); ?>/assets/images/pets/small-dogs@2x.webp">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/pets/small-dogs@2x.png" width="100"
            height="150" alt="Мальнькая собака." loading="lazy">
        </picture>
        <h3 class="pets__subtitle">Малькие<br>собаки</h3></li>
      <li class="pets__item pets__item--puppy">
        <picture>
          <source type="image/avif"
            srcset="<?php echo get_template_directory_uri(); ?>/assets/images/pets/puppy@2x.avif">
          <source type="image/webp"
            srcset="<?php echo get_template_directory_uri(); ?>/assets/images/pets/puppy@2x.webp">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/pets/puppy@2x.png" width="100"
            height="150" alt="Щенок." loading="lazy">
        </picture>
        <h3 class="pets__subtitle">Щенки</h3></li>
      <li class="pets__item pets__item--long">
        <picture>
          <source type="image/avif"
            srcset="<?php echo get_template_directory_uri(); ?>/assets/images/pets/long-haired-dogs@2x.avif">
          <source type="image/webp"
            srcset="<?php echo get_template_directory_uri(); ?>/assets/images/pets/long-haired-dogs@2x.webp">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/pets/long-haired-dogs@2x.png" width="100"
            height="150" alt="Длинношерстная собака." loading="lazy">
        </picture>
        <h3 class="pets__subtitle">Cобаки c&nbsp;длинной шерстью</h3></li>
      <li class="pets__item pets__item--curly">
        <picture>
          <source type="image/avif"
            srcset="<?php echo get_template_directory_uri(); ?>/assets/images/pets/curly-dogs@2x.avif">
          <source type="image/webp"
            srcset="<?php echo get_template_directory_uri(); ?>/assets/images/pets/curly-dogs@2x.webp">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/pets/curly-dogs@2x.png" width="100"
            height="150" alt="Кудрявая собака." loading="lazy">
        </picture>
        <h3 class="pets__subtitle">Собаки с&nbsp;кудрявой шерстью</h3></li>
      <li class="pets__item pets__item--cat">
        <picture>
          <source type="image/avif" srcset="<?php echo get_template_directory_uri(); ?>/assets/images/pets/cat@2x.avif">
          <source type="image/webp" srcset="<?php echo get_template_directory_uri(); ?>/assets/images/pets/cat@2x.webp">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/pets/cat@2x.png" width="100" height="150"
            alt="Кошка." loading="lazy">
        </picture>
        <h3 class="pets__subtitle">Кошки</h3></li>
      <li class="pets__item pets__item--kitty">
        <picture>
          <source type="image/avif"
            srcset="<?php echo get_template_directory_uri(); ?>/assets/images/pets/kitty@2x.avif">
          <source type="image/webp"
            srcset="<?php echo get_template_directory_uri(); ?>/assets/images/pets/kitty@2x.webp">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/pets/kitty@2x.png" width="100"
            height="150" alt="Котенок." loading="lazy">
        </picture>
        <h3 class="pets__subtitle">Котята</h3></li>
      <li class="pets__item pets__item--ither">
        <picture>
          <source type="image/avif"
            srcset="<?php echo get_template_directory_uri(); ?>/assets/images/pets/other@2x.avif">
          <source type="image/webp"
            srcset="<?php echo get_template_directory_uri(); ?>/assets/images/pets/other@2x.webp">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/pets/other@2x.png" width="100"
            height="150" alt="Кролик, хомяк, хорек." loading="lazy">
        </picture>
        <h3 class="pets__subtitle">И другие</h3></li>
    </ul>
  </div>
</section>
