<?php
//
///* =============================================================================
// *  THEME_PICTURE (массив или ID) + STATIC_IMAGE
// * ========================================================================== */
//
//function theme_picture_resolve_alt( $attachment, string $alt_override = '' ): string {
//	if ( $alt_override !== '' ) {
//		return $alt_override;
//	}
//
//	$id = 0;
//	if ( is_numeric( $attachment ) ) {
//		$id = (int) $attachment;
//	} elseif ( is_array( $attachment ) && ! empty( $attachment['ID'] ) ) {
//		$id = (int) $attachment['ID'];
//	}
//	if ( $id ) {
//		$meta_alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
//		if ( is_string( $meta_alt ) && $meta_alt !== '' ) {
//			return $meta_alt;
//		}
//		$title = get_the_title( $id );
//		if ( is_string( $title ) && $title !== '' ) {
//			return $title;
//		}
//	}
//
//	return '';
//}
//
//function theme_picture_safe_ext( string $url, string $ext, bool $check_file = false ): string {
//	if ( $url === '' ) {
//		return $url;
//	}
//
//	$base    = $url;
//	$tail    = '';
//	$matches = [];
//	if ( preg_match( '~^(.+?)([?#].*)$~', $url, $matches ) ) {
//		$base = $matches[1];
//		$tail = $matches[2];
//	}
//
//	$base      = preg_replace( '~\.(jpe?g|png|gif|bmp|webp|avif)$~i', '', $base );
//	$candidate = $base . '.' . $ext . $tail;
//
//	if ( ! $check_file ) {
//		return $candidate;
//	}
//
//	$uploads = wp_get_upload_dir();
//	$baseurl = trailingslashit( $uploads['baseurl'] );
//	$basedir = trailingslashit( $uploads['basedir'] );
//
//	if ( strpos( $candidate, $baseurl ) === 0 ) {
//		$rel = ltrim( substr( $candidate, strlen( $baseurl ) ), '/' );
//		if ( $rel !== '' && file_exists( $basedir . $rel ) ) {
//			return $candidate;
//		}
//
//		return '';
//	}
//
//	return $candidate;
//}
//
//function theme_picture_get_image_data( int $attachment_id, string $size, ?string $fallback_url = null ): array {
//	$src     = wp_get_attachment_image_src( $attachment_id, $size );
//	$src_url = $src[0] ?? $fallback_url ?? '';
//
//	return [
//		'src'    => (string) $src_url,
//		'width'  => (int) ( $src[1] ?? 0 ),
//		'height' => (int) ( $src[2] ?? 0 ),
//	];
//}
//
//function theme_picture_srcset_sizes( int $attachment_id, string $size, ?string $sizes_override = null ): array {
//	$srcset = wp_get_attachment_image_srcset( $attachment_id, $size ) ?: '';
//	$sizes  = $sizes_override ?? ( wp_get_attachment_image_sizes( $attachment_id, $size ) ?: '' );
//
//	return [
//		'srcset' => (string) $srcset,
//		'sizes'  => (string) $sizes,
//	];
//}
//
//function theme_picture( $attachment, array $args = [] ): void {
//	$a = array_merge( [
//		'img_size'           => 'thumb-350',
//		'fancybox'           => false,
//		'fancybox_size'      => 'full',
//		'class'              => '',
//		'img_class'          => '',
//		'altImg'             => '',
//		'media_sources'      => [],
//		'formats'            => [ 'avif', 'webp' ], // вывод в <source>
//		'generate_formats'   => [ 'avif', 'webp' ], // автогенерация файлов
//		'fancybox_prefix'    => 'gallery',
//		'fancy_attrs'        => [],
//		'video_attr'         => [], // на <a> для видео
//		'sizes_attr'         => null,
//		'loading'            => 'lazy',
//		'decoding'           => 'async',
//		'check_format_files' => false,
//		'play_label'         => 'Play video',
//	], $args );
//
//	$formats = array_values( array_intersect( (array) $a['formats'], [ 'avif', 'webp' ] ) );
//
//	// Превращаем ID в массив как у wp_prepare_attachment_for_js
//	if ( is_numeric( $attachment ) ) {
//		$attachment = wp_prepare_attachment_for_js( (int) $attachment );
//	}
//
//	// Плейсхолдер при отсутствии
//	if ( ! is_array( $attachment ) || empty( $attachment['url'] ) ) {
//		// можно подставить свою заглушку из медиа, если нужно
//		return;
//	}
//
//	$idFile       = (int) ( $attachment['ID'] ?? 0 );
//	$imgSize      = $attachment['sizes'][ $a['img_size'] ] ?? null;
//	$fancyImgFile = $attachment['sizes'][ $a['fancybox_size'] ] ?? ( $attachment['url'] ?? null );
//	$mime         = $idFile ? get_post_mime_type( $idFile ) : '';
//	$is_video     = ( is_string( $mime ) && strpos( $mime, 'video/' ) === 0 );
//	$href         = $attachment['url'];
//	$alt          = theme_picture_resolve_alt( $attachment, $a['altImg'] );
//
//	// Автогенерация недостающих сабсайзов/форматов только для изображений
//	if ( ! empty( $idFile ) && ! $is_video && ( empty( $attachment['sizes'] ) || empty( $imgSize ) ) ) {
//		$lock = 'theme_gen_lock_' . $idFile;
//		if ( ! get_transient( $lock ) ) {
//			set_transient( $lock, 1, 15 );
//			if ( function_exists( 'theme_generate_ensure_sizes' ) ) {
//				try {
//					theme_generate_ensure_sizes( $idFile, [ $a['img_size'] ], true );
//				} catch ( Throwable $e ) {
//					error_log( '[theme_picture] ensure_sizes: ' . $e->getMessage() );
//				}
//			}
//			if ( ! empty( $a['generate_formats'] ) && function_exists( 'theme_generate_alt_formats_for_attachment' ) ) {
//				try {
//					theme_generate_alt_formats_for_attachment( $idFile, null, $a['generate_formats'] );
//				} catch ( Throwable $e ) {
//					error_log( '[theme_picture] alt_formats: ' . $e->getMessage() );
//				}
//			}
//			delete_transient( $lock );
//		}
//		$new = wp_prepare_attachment_for_js( $idFile );
//		if ( is_array( $new ) && ! empty( $new ) ) {
//			$attachment   = $new;
//			$imgSize      = $attachment['sizes'][ $a['img_size'] ] ?? ( $attachment['url'] ?? $imgSize );
//			$fancyImgFile = $attachment['sizes'][ $a['fancybox_size'] ] ?? ( $attachment['url'] ?? $fancyImgFile );
//		}
//	}
//
//	// подстраховка
//	if ( empty( $imgSize ) ) {
//		$imgSize = $attachment['sizes']['thumbnail'] ?? ( $attachment['url'] ?? '' );
//	}
//	if ( $a['fancybox'] && empty( $fancyImgFile ) ) {
//		$fancyImgFile = $attachment['url'] ?? '';
//	}
//
//	$primary      = theme_picture_get_image_data( $idFile, $a['img_size'], $imgSize ?: null );
//	$srcset_sizes = $idFile ? theme_picture_srcset_sizes( $idFile, $a['img_size'], $a['sizes_attr'] ) : [ 'srcset' => '', 'sizes' => '' ];
//	$as_ext       = function ( string $url, string $ext ) use ( $a ): string {
//		return theme_picture_safe_ext( $url, $ext, (bool) $a['check_format_files'] );
//	};
//	if ( $a['fancybox'] && $idFile ) {
//		$fancy = theme_picture_get_image_data( $idFile, $a['fancybox_size'], $fancyImgFile ?: null );
//		if ( ! empty( $fancy['src'] ) ) {
//			$fancyImgFile = $fancy['src'];
//		}
//	}
//
//	$print_picture = function () use ( $a, $formats, $attachment, $primary, $srcset_sizes, $is_video, $alt, $as_ext, $idFile ) {
//		echo "<picture>\n";
//		foreach ( (array) $a['media_sources'] as $m ) {
//			$sizeKey = $m['size'] ?? null;
//			$media   = $m['media'] ?? null;
//			if ( ! $sizeKey || ! $media ) {
//				continue;
//			}
//			$srcDefault = $idFile ? wp_get_attachment_image_url( $idFile, $sizeKey ) : '';
//			$srcDefault = $srcDefault ?: (string) ( $attachment['sizes'][ $sizeKey ] ?? '' );
//			if ( $srcDefault === '' ) {
//				continue;
//			}
//			foreach ( $formats as $fmt ) {
//				$altSrc = $as_ext( $srcDefault, $fmt );
//				if ( $altSrc !== '' ) {
//					echo '<source media="' . esc_attr( $media ) . '" type="image/' . esc_attr( $fmt ) . '" srcset="' . esc_url( $altSrc ) . '">' . "\n";
//				}
//			}
//			echo '<source media="' . esc_attr( $media ) . '" srcset="' . esc_url( $srcDefault ) . '">' . "\n";
//		}
//		$srcDefaultBase = (string) $primary['src'];
//		foreach ( $formats as $fmt ) {
//			$altSrc = $as_ext( $srcDefaultBase, $fmt );
//			if ( $altSrc !== '' ) {
//				echo '<source type="image/' . esc_attr( $fmt ) . '" srcset="' . esc_url( $altSrc ) . '">' . "\n";
//			}
//		}
//
//		$img_classes = trim( $a['img_class'] . ( $is_video ? ' is-video-poster' : ' is-image' ) );
//		$loading     = in_array( $a['loading'], [ 'lazy', 'eager', 'auto' ], true ) ? $a['loading'] : 'lazy';
//		$decoding    = in_array( $a['decoding'], [ 'async', 'sync', 'auto' ], true ) ? $a['decoding'] : 'async';
//		echo '<img class="' . esc_attr( $img_classes ) . '" src="' . esc_url( $srcDefaultBase ) . '" alt="' . esc_attr( $alt ) . '"';
//		if ( ! empty( $primary['width'] ) && ! empty( $primary['height'] ) ) {
//			echo ' width="' . (int) $primary['width'] . '" height="' . (int) $primary['height'] . '"';
//		}
//		if ( $srcset_sizes['srcset'] !== '' ) {
//			echo ' srcset="' . esc_attr( $srcset_sizes['srcset'] ) . '"';
//		}
//		if ( $srcset_sizes['sizes'] !== '' ) {
//			echo ' sizes="' . esc_attr( $srcset_sizes['sizes'] ) . '"';
//		}
//		echo ' loading="' . esc_attr( $loading ) . '" decoding="' . esc_attr( $decoding ) . "\">\n";
//		echo "</picture>\n";
//	};
//
//	$wrap_classes  = trim( $a['class'] . ' ' . ( $is_video ? 'is-video' : 'is-image' ) );
//	$data_fancybox = esc_attr( $a['fancybox_prefix'] ?: 'gallery' );
//
//	// Для видео — показываем только через Fancybox (без инлайнового <video>)
//	if ( $is_video ) {
//		$aria_label = $alt ?: $a['play_label'];
//		echo '<a class="' . esc_attr( $wrap_classes ) . '" href="' . esc_url( $href ) . '" data-fancybox="' . $data_fancybox . '" aria-label="' . esc_attr( $aria_label ) . '" role="button"';
//		foreach ( (array) $a['fancy_attrs'] as $k => $v ) {
//			echo ' ' . esc_attr( $k ) . '="' . esc_attr( $v ) . '"';
//		}
//		foreach ( (array) $a['video_attr'] as $k => $v ) {
//			echo ' ' . esc_attr( $k ) . '="' . esc_attr( $v ) . '"';
//		}
//		echo '>' . "\n";
//		// постер берём из thumbnail видео
//		$poster_id = (int) get_post_thumbnail_id( (int) $attachment['ID'] );
//		if ( $poster_id ) {
//			theme_picture_static_image( $poster_id, [
//				'img_size'          => $a['img_size'],
//				'wrapper'           => null, // ВАЖНО: без <div> внутри <a>
//				'img_class'         => trim( $a['img_class'] . ' is-video-poster' ),
//				'picture_class'     => '', // при желании можно добавить
//				'alt'               => $a['altImg'] ?: get_the_title( (int) $attachment['ID'] ),
//				'media_sources'     => $a['media_sources'],
//				'formats'           => $formats,
//				'sizes_attr'        => $a['sizes_attr'],
//				'loading'           => $a['loading'],
//				'decoding'          => $a['decoding'],
//				'check_format_files'=> $a['check_format_files'],
//			] );
//		} else {
//			echo '<span class="video-play-btn" aria-hidden="true">▶</span>';
//		}
//		echo "</a>\n";
//
//		return;
//	}
//
//	if ( $a['fancybox'] && $href ) {
//		$link_href = $fancyImgFile ?: $href;
//		echo '<a class="' . esc_attr( $wrap_classes ) . '" href="' . esc_url( $link_href ) . '" data-fancybox="' . $data_fancybox . '"';
//		foreach ( (array) $a['fancy_attrs'] as $k => $v ) {
//			echo ' ' . esc_attr( $k ) . '="' . esc_attr( $v ) . '"';
//		}
//		echo '>' . "\n";
//		$print_picture();
//		echo "</a>\n";
//	} else {
//		echo '<div class="' . esc_attr( $wrap_classes ) . '">' . "\n";
//		$print_picture();
//		echo "</div>\n";
//	}
//}
//
//function theme_picture_static_image( int $attachment_id, array $args = [] ): void {
//	$a = array_merge( [
//		'img_size'           => 'thumb-350',
//		'class'              => '',         // класс обёртки (если обёртка есть)
//		'picture_class'      => '',         // класс на <picture>
//		'img_class'          => '',
//		'alt'                => '',
//		'media_sources'      => [],
//		'wrapper'            => 'div',      // 'div' | null — если null, выводим БЕЗ обёртки
//		'formats'            => [ 'avif', 'webp' ],
//		'sizes_attr'         => null,
//		'loading'            => 'lazy',
//		'decoding'           => 'async',
//		'check_format_files' => false,
//	], $args );
//
//	$src_data = theme_picture_get_image_data( $attachment_id, $a['img_size'] );
//	if ( empty( $src_data['src'] ) ) {
//		return;
//	}
//
//	$srcset_sizes = theme_picture_srcset_sizes( $attachment_id, $a['img_size'], $a['sizes_attr'] );
//	$as_ext       = fn( string $url, string $ext ) => theme_picture_safe_ext( $url, $ext, (bool) $a['check_format_files'] );
//	$formats      = array_values( array_intersect( (array) $a['formats'], [ 'avif', 'webp' ] ) );
//	$alt          = theme_picture_resolve_alt( $attachment_id, $a['alt'] );
//
//	$open  = $a['wrapper'] ? '<' . $a['wrapper'] . ' class="' . esc_attr( $a['class'] ) . '">' . "\n" : '';
//	$close = $a['wrapper'] ? '</' . $a['wrapper'] . ">\n" : '';
//
//	echo $open;
//	echo '<picture' . ( $a['picture_class'] ? ' class="' . esc_attr( $a['picture_class'] ) . '"' : '' ) . '>' . "\n";
//
//	foreach ( (array) $a['media_sources'] as $m ) {
//		if ( empty( $m['media'] ) || empty( $m['size'] ) ) {
//			continue;
//		}
//		$u = wp_get_attachment_image_url( $attachment_id, $m['size'] );
//		if ( ! $u ) {
//			continue;
//		}
//		foreach ( $formats as $fmt ) {
//			$alt_src = $as_ext( $u, $fmt );
//			if ( $alt_src !== '' ) {
//				echo '<source media="' . esc_attr( $m['media'] ) . '" type="image/' . esc_attr( $fmt ) . '" srcset="' . esc_url( $alt_src ) . '">' . "\n";
//			}
//		}
//		echo '<source media="' . esc_attr( $m['media'] ) . '" srcset="' . esc_url( $u ) . '">' . "\n";
//	}
//
//	foreach ( $formats as $fmt ) {
//		$alt_src = $as_ext( $src_data['src'], $fmt );
//		if ( $alt_src !== '' ) {
//			echo '<source type="image/' . esc_attr( $fmt ) . '" srcset="' . esc_url( $alt_src ) . '">' . "\n";
//		}
//	}
//	$loading  = in_array( $a['loading'], [ 'lazy', 'eager', 'auto' ], true ) ? $a['loading'] : 'lazy';
//	$decoding = in_array( $a['decoding'], [ 'async', 'sync', 'auto' ], true ) ? $a['decoding'] : 'async';
//	echo '<img class="' . esc_attr( $a['img_class'] ) . '" src="' . esc_url( $src_data['src'] ) . '" alt="' . esc_attr( $alt ) . '"';
//	if ( ! empty( $src_data['width'] ) && ! empty( $src_data['height'] ) ) {
//		echo ' width="' . (int) $src_data['width'] . '" height="' . (int) $src_data['height'] . '"';
//	}
//	if ( $srcset_sizes['srcset'] !== '' ) {
//		echo ' srcset="' . esc_attr( $srcset_sizes['srcset'] ) . '"';
//	}
//	if ( $srcset_sizes['sizes'] !== '' ) {
//		echo ' sizes="' . esc_attr( $srcset_sizes['sizes'] ) . '"';
//	}
//	echo ' loading="' . esc_attr( $loading ) . '" decoding="' . esc_attr( $decoding ) . "\">\n";
//	echo "</picture>\n";
//	echo $close;
//}
