<?php
///**
// * Минимальный, но полный набор:
// * - theme_picture($attachment, $args) + theme_picture_static_image()
// * - Генерация размеров (bg-1920, thumb-350, medium-768) и форматов (AVIF/WebP) + UI + AJAX
// * - Кнопка и бэкенд генерации постера для видео (FFmpeg), привязка как thumbnail
// * - Синк sizes постера в мету видео (для медиабиблиотеки)
// * - Удаление: картинки — чистим AVIF/WebP и отвязываем; видео — удаляем постер и его размеры/форматы
// */
//
///* =============================================================================
// *  НАСТРОЙКИ/РАЗМЕРЫ/КАЧЕСТВО
// * ========================================================================== */
//
//add_action( 'after_setup_theme', function () {
//	add_theme_support( 'post-thumbnails' );
//	add_image_size( 'bg-1920', 1920, 0, false );
//	add_image_size( 'thumb-350', 350, 0, false );
//	add_image_size( 'medium-768', 768, 0, false );
//} );
//add_filter( 'image_size_names_choose', fn( $s ) => $s + [
//		'bg-1920'    => 'BG 1920',
//		'thumb-350'  => 'Thumb 350',
//		'medium-768' => 'Medium 768',
//	] );
//add_filter( 'jpeg_quality', fn() => 90 );
//add_filter( 'wp_editor_set_quality', fn() => 90 );
//add_filter( 'mime_types', function ( $m ) {
//	$m['webp'] = 'image/webp';
//	$m['avif'] = 'image/avif';
//
//	return $m;
//} );
//add_filter( 'big_image_size_threshold', fn() => 1920, 10 );
//
///* =============================================================================
// *  ВСПОМОГАТЕЛЬНЫЕ (пути/удаление)
// * ========================================================================== */
//
//function theme_guess_variant_paths( string $abs ): array {
//	$info = pathinfo( $abs );
//	$d    = $info['dirname'] ?? '';
//	$n    = $info['filename'] ?? '';
//
//	return ( $d && $n ) ? [ "$d/$n.avif", "$d/$n.webp" ] : [];
//}
//
//function theme_safe_unlink( string $abs ): void {
//	if ( is_file( $abs ) ) {
//		function_exists( 'wp_delete_file' ) ? wp_delete_file( $abs ) : @unlink( $abs );
//	}
//}
//
///* =============================================================================
// *  ОЧИСТКА ЛИШНИХ РАЗМЕРОВ В МЕТАДАННЫХ + МЭППИНГ STANDARD→CUSTOM
// * ========================================================================== */
//
//function theme_allowed_sizes(): array {
//	return [ 'bg-1920', 'thumb-350', 'medium-768' ];
//}
//
//function theme_strip_unwanted_sizes_from_meta( array $meta ): array {
//	if ( ! empty( $meta['sizes'] ) && is_array( $meta['sizes'] ) ) {
//		$allow = array_flip( theme_allowed_sizes() );
//		foreach ( $meta['sizes'] as $k => $v ) {
//			if ( ! isset( $allow[ $k ] ) ) {
//				unset( $meta['sizes'][ $k ] );
//			}
//		}
//	}
//
//	return $meta;
//}
//
//add_filter( 'wp_generate_attachment_metadata', function ( $meta ) {
//	return theme_strip_unwanted_sizes_from_meta( $meta );
//}, 9999 );
//
//if ( ! defined( 'THEME_AUTOGEN_MAPPED_SIZES' ) ) {
//	define( 'THEME_AUTOGEN_MAPPED_SIZES', true );
//}
//
//function theme_size_map(): array {
//	return [
//		'thumbnail'    => 'thumb-350',
//		'medium'       => 'thumb-350',
//		'medium_large' => 'medium-768',
//		'large'        => 'bg-1920',
//		'1536x1536'    => 'bg-1920',
//		'2048x2048'    => 'bg-1920',
//	];
//}
//
//add_filter( 'image_downsize', function ( $out, $attachment_id, $size ) {
//	if ( ! is_string( $size ) ) {
//		return $out;
//	}
//	$map = theme_size_map();
//	if ( ! isset( $map[ $size ] ) ) {
//		return $out;
//	}
//
//	$mapped = $map[ $size ];
//
//	if ( THEME_AUTOGEN_MAPPED_SIZES ) {
//		$meta = wp_get_attachment_metadata( $attachment_id );
//		if ( empty( $meta['sizes'][ $mapped ] ) && function_exists( 'theme_generate_ensure_sizes' ) ) {
//			theme_generate_ensure_sizes( $attachment_id, [ $mapped ], true );
//		}
//	}
//
//	$img = image_get_intermediate_size( $attachment_id, $mapped );
//	if ( $img && ! empty( $img['url'] ) ) {
//		return [ $img['url'], (int) ( $img['width'] ?? 0 ), (int) ( $img['height'] ?? 0 ), true ];
//	}
//
//	$full = wp_get_attachment_url( $attachment_id );
//	$meta = $meta ?? wp_get_attachment_metadata( $attachment_id );
//
//	return [ $full, (int) ( $meta['width'] ?? 0 ), (int) ( $meta['height'] ?? 0 ), false ];
//}, 9, 3 );
//
///* =============================================================================
// *  ТОЧЕЧНАЯ ГЕНЕРАЦИЯ РАЗМЕРОВ И ФОРМАТОВ (ДЛЯ КАРТИНОК)
// * ========================================================================== */
//
//function theme_generate_ensure_sizes( int $attachment_id, array $wanted_sizes, bool $force = false ): array {
//	$wanted_sizes = array_values( array_unique( array_filter( $wanted_sizes ) ) );
//	$meta         = wp_get_attachment_metadata( $attachment_id ) ?: [];
//	if ( ! $wanted_sizes ) {
//		return $meta;
//	}
//
//	$file = get_attached_file( $attachment_id );
//	if ( ! $file || ! file_exists( $file ) ) {
//		return $meta;
//	}
//
//	$defs = wp_get_additional_image_sizes();
//	$info = pathinfo( $file );
//	foreach ( $wanted_sizes as $size_key ) {
//		if ( empty( $defs[ $size_key ] ) ) {
//			continue;
//		}
//		if ( ! empty( $meta['sizes'][ $size_key ] ) && ! $force ) {
//			continue;
//		}
//
//		$d    = $defs[ $size_key ];
//		$dw   = (int) $d['width'];
//		$dh   = (int) $d['height'];
//		$crop = (bool) $d['crop'];
//		$ed   = wp_get_image_editor( $file );
//		if ( is_wp_error( $ed ) ) {
//			continue;
//		}
//		$orig = $ed->get_size();
//		if ( ! $crop && $dh === 0 && ! empty( $orig['width'] ) ) {
//			$dh = (int) round( ( $orig['height'] ?? 0 ) * ( $dw / $orig['width'] ) );
//		}
//		if ( $orig && ( $orig['width'] != $dw || $orig['height'] != $dh ) ) {
//			$ed->resize( $dw, $dh, $crop );
//		}
//
//		$dest  = $info['dirname'] . '/' . $info['filename'] . '-' . $dw . 'x' . $dh . '.' . $info['extension'];
//		$saved = $ed->save( $dest );
//		if ( ! is_wp_error( $saved ) ) {
//			$meta['sizes'][ $size_key ] = [
//				'file'      => basename( $dest ),
//				'width'     => (int) $saved['width'],
//				'height'    => (int) $saved['height'],
//				'mime-type' => $saved['mime-type'],
//			];
//		}
//	}
//	$meta = theme_strip_unwanted_sizes_from_meta( $meta );
//	wp_update_attachment_metadata( $attachment_id, $meta );
//
//	return $meta;
//}
//
//function theme_generate_alt_formats_for_attachment(
//	int $attachment_id, ?array $metadata = null, ?array $formats_override = null
//): array {
//	$metadata = $metadata ?: wp_get_attachment_metadata( $attachment_id );
//	if ( ! $metadata ) {
//		return [];
//	}
//
//	$uploads = wp_get_upload_dir();
//	$basedir = trailingslashit( $uploads['basedir'] );
//	$baseurl = trailingslashit( $uploads['baseurl'] );
//
//	$formats = $formats_override;
//	if ( $formats === null ) {
//		$formats = get_post_meta( $attachment_id, '_gen_formats', true ) ?: [];
//	}
//	$formats = array_values( array_intersect( (array) $formats, [ 'avif', 'webp' ] ) );
//	if ( ! $formats ) {
//		return $metadata;
//	}
//
//	$supports = [
//		'avif' => function_exists( 'wp_image_editor_supports' ) && wp_image_editor_supports( [ 'mime_type' => 'image/avif' ] ),
//		'webp' => function_exists( 'wp_image_editor_supports' ) && wp_image_editor_supports( [ 'mime_type' => 'image/webp' ] ),
//	];
//	if ( ! in_array( true, $supports, true ) ) {
//		return $metadata;
//	}
//
//	$mk    = function ( string $abs, string $fmt ) {
//		$ed = wp_get_image_editor( $abs );
//		if ( is_wp_error( $ed ) ) {
//			return false;
//		}
//		$mime = ( $fmt === 'avif' ) ? 'image/avif' : 'image/webp';
//		$info = pathinfo( $abs );
//		$out  = $info['dirname'] . '/' . $info['filename'] . '.' . $fmt;
//		if ( file_exists( $out ) ) {
//			return true;
//		}
//
//		return ! is_wp_error( $ed->save( $out, $mime ) );
//	};
//	$paths = function ( string $abs ) use ( $basedir, $baseurl ) {
//		$info    = pathinfo( $abs );
//		$dir     = $info['dirname'];
//		$name    = $info['filename'];
//		$url_dir = $baseurl . ltrim( str_replace( $basedir, '', $dir . '/' ), '/' );
//
//		return [
//			'avif_abs' => "$dir/$name.avif",
//			'webp_abs' => "$dir/$name.webp",
//			'avif_url' => "$url_dir$name.avif",
//			'webp_url' => "$url_dir$name.webp"
//		];
//	};
//
//	$files = [];
//	if ( ! empty( $metadata['file'] ) && file_exists( $basedir . $metadata['file'] ) ) {
//		$files[] = [ 'type' => 'original', 'abs' => $basedir . $metadata['file'] ];
//	}
//	if ( ! empty( $metadata['sizes'] ) ) {
//		$folder = ! empty( $metadata['file'] ) ? dirname( $metadata['file'] ) : '';
//		foreach ( $metadata['sizes'] as $key => $s ) {
//			if ( ! empty( $s['file'] ) && file_exists( $basedir . $folder . '/' . $s['file'] ) ) {
//				$files[] = [ 'type' => 'size', 'size' => $key, 'abs' => $basedir . $folder . '/' . $s['file'] ];
//			}
//		}
//	}
//
//	foreach ( $files as $f ) {
//		$pp = $paths( $f['abs'] );
//		foreach ( $formats as $fmt ) {
//			$fmt = ( $fmt === 'avif' ) ? 'avif' : 'webp';
//			$mk( $f['abs'], $fmt );
//		}
//		$has = [
//			'avif' => file_exists( $pp['avif_abs'] ) ? $pp['avif_url'] : null,
//			'webp' => file_exists( $pp['webp_abs'] ) ? $pp['webp_url'] : null,
//		];
//		if ( $f['type'] === 'original' ) {
//			$metadata['sources'] = array_filter( $has );
//		} else {
//			$metadata['sizes'][ $f['size'] ]['sources'] = array_filter( $has );
//		}
//	}
//	$metadata = theme_strip_unwanted_sizes_from_meta( $metadata );
//	wp_update_attachment_metadata( $attachment_id, $metadata );
//
//	return $metadata;
//}
//
///* =============================================================================
// *  UI В КАРТОЧКЕ ВЛОЖЕНИЯ (sizes/formats) + AJAX "Generate now"
// * ========================================================================== */
//
//function theme_gen_get_sizes_list(): array {
//	return [
//		'bg-1920'    => 'BG 1920',
//		'thumb-350'  => 'Thumb 350',
//		'medium-768' => 'Medium 768',
//	];
//}
//
//function theme_gen_get_formats_list(): array {
//	return [ 'avif' => 'AVIF', 'webp' => 'WebP' ];
//}
//
//add_filter( 'attachment_fields_to_edit', function ( $fields, $post ) {
//	if ( $post->post_mime_type && strpos( $post->post_mime_type, 'image/' ) !== 0 ) {
//		return $fields;
//	}
//
//	$human = function ( $bytes ) {
//		$bytes = (int) $bytes;
//		if ( $bytes <= 0 ) {
//			return '0 B';
//		}
//		$u = [ 'B', 'KB', 'MB', 'GB', 'TB' ];
//		$p = min( (int) floor( log( $bytes, 1024 ) ), count( $u ) - 1 );
//
//		return number_format( $bytes / ( 1024 ** $p ), $p ? 2 : 0, '.', ' ' ) . ' ' . $u[ $p ];
//	};
//
//	$sizes_wanted   = get_post_meta( $post->ID, '_gen_sizes', true ) ?: [];
//	$formats_wanted = get_post_meta( $post->ID, '_gen_formats', true ) ?: [];
//
//	$meta    = wp_get_attachment_metadata( $post->ID );
//	$uploads = wp_get_upload_dir();
//	$baseurl = trailingslashit( $uploads['baseurl'] );
//	$basedir = trailingslashit( $uploads['basedir'] );
//	$folder  = ! empty( $meta['file'] ) ? dirname( $meta['file'] ) : '';
//	$folder  = ltrim( $folder, '/' );
//
//	$size_file_rel = function ( string $size_key ) use ( $meta, $folder ) {
//		return empty( $meta['sizes'][ $size_key ]['file'] ) ? null : ( ( $folder ? $folder . '/' : '' ) . $meta['sizes'][ $size_key ]['file'] );
//	};
//	$size_file_url = fn( string $size_key ) => ( $rel = $size_file_rel( $size_key ) ) ? $baseurl . $rel : null;
//	$size_file_abs = fn( string $size_key ) => ( $rel = $size_file_rel( $size_key ) ) ? $basedir . $rel : null;
//	$size_exists   = fn( string $size_key ) => ( $abs = $size_file_abs( $size_key ) ) && is_file( $abs );
//
//	$format_url    = function ( string $fmt, ?string $size_key = null ) use ( $meta ) {
//		return ( $size_key === null || $size_key === 'full' ) ? ( $meta['sources'][ $fmt ] ?? null ) : ( $meta['sizes'][ $size_key ]['sources'][ $fmt ] ?? null );
//	};
//	$format_abs    = function ( string $fmt, ?string $size_key = null ) use ( $uploads, $format_url ) {
//		$url = $format_url( $fmt, $size_key );
//
//		return $url ? str_replace( $uploads['baseurl'], $uploads['basedir'], $url ) : null;
//	};
//	$format_exists = fn( string $fmt, ?string $size_key = null ) => ( $abs = $format_abs( $fmt,
//			$size_key ) ) && is_file( $abs );
//
//	$styles = '<style>.tg-badge{display:inline-block;padding:.15em .45em;border-radius:4px;font-size:12px;margin-left:.5em;vertical-align:middle}.tg-ok{background:#e7f7ee;color:#137a41;border:1px solid #b6e5c9}.tg-miss{background:#f7f7f7;color:#666;border:1px solid #ddd}.tg-note{color:#888;margin-left:.5em}.tg-row{margin:.25em 0}.tg-row small{color:#666}</style>';
//
//	// sizes
//	$sizes_html = $styles;
//	foreach ( theme_gen_get_sizes_list() as $key => $label ) {
//		$exists = $size_exists( $key );
//		$url    = $exists ? $size_file_url( $key ) : null;
//		$abs    = $exists ? $size_file_abs( $key ) : null;
//		$w      = $meta['sizes'][ $key ]['width'] ?? null;
//		$h      = $meta['sizes'][ $key ]['height'] ?? null;
//		$fs     = ( $abs && is_file( $abs ) ) ? $human( filesize( $abs ) ) : null;
//
//		$checked  = in_array( $key, (array) $sizes_wanted, true );
//		$disabled = $exists;
//		$badgeTxt = $exists ? ( '✓ есть' . ( $w && $h ? ' ' . $w . '×' . $h : '' ) . ( $fs ? ' — ' . $fs : '' ) ) : '— нет';
//		$badge    = $exists ? '<a href="' . esc_url( $url ) . '" target="_blank" class="tg-badge tg-ok">' . $badgeTxt . '</a>' : '<span class="tg-badge tg-miss">' . $badgeTxt . '</span>';
//
//		$hidden_keep = ( $disabled && $checked ) ? '<input type="hidden" name="attachments[' . $post->ID . '][gen_sizes][]" value="' . esc_attr( $key ) . '">' : '';
//
//		$sizes_html .= '<div class="tg-row"><label><input type="checkbox" name="attachments[' . $post->ID . '][gen_sizes][]" value="' . esc_attr( $key ) . '" ' . checked( $checked,
//				true, false ) . ' ' . disabled( $disabled, true,
//				false ) . '> ' . esc_html( $label ) . '</label>' . $badge . ( $disabled ? '<span class="tg-note"> (уже создан)</span>' : '' ) . $hidden_keep . '</div>';
//	}
//	$fields['theme_gen_sizes'] = [
//		'label' => __( 'Generate sizes' ),
//		'input' => 'html',
//		'html'  => $sizes_html,
//		'helps' => __( 'Select sizes to generate' )
//	];
//
//	// formats
//	$formats_html = '<div style="margin:.5em 0 0">';
//	$targets      = array_merge( [ 'full' ], array_keys( theme_gen_get_sizes_list() ) );
//	foreach ( theme_gen_get_formats_list() as $fmt_key => $fmt_label ) {
//		$formats_html .= '<div style="margin:.35em 0;"><strong>' . esc_html( $fmt_label ) . '</strong>: ';
//		$have         = 0;
//		$total        = 0;
//		foreach ( $targets as $t ) {
//			$total ++;
//			$ok = $format_exists( $fmt_key, $t === 'full' ? null : $t );
//			if ( $ok ) {
//				$have ++;
//			}
//			if ( $t === 'full' ) {
//				$w = $meta['width'] ?? null;
//				$h = $meta['height'] ?? null;
//			} else {
//				$w = $meta['sizes'][ $t ]['width'] ?? null;
//				$h = $meta['sizes'][ $t ]['height'] ?? null;
//			}
//
//			$label_t = ( $t === 'full' ) ? 'original' : $t;
//			if ( $ok ) {
//				$abs          = $format_abs( $fmt_key, $t === 'full' ? null : $t );
//				$fs           = ( $abs && is_file( $abs ) ) ? $human( filesize( $abs ) ) : null;
//				$txt          = '✓ ' . $label_t . ( $w && $h ? ' ' . $w . '×' . $h : '' ) . ( $fs ? ' — ' . $fs : '' );
//				$url          = $format_url( $fmt_key, $t === 'full' ? null : $t );
//				$formats_html .= '<a href="' . esc_url( $url ) . '" target="_blank" class="tg-badge tg-ok">' . $txt . '</a> ';
//			} else {
//				$formats_html .= '<span class="tg-badge tg-miss">— ' . $label_t . '</span> ';
//			}
//		}
//		$fmt_checked  = in_array( $fmt_key, (array) $formats_wanted, true );
//		$fmt_disabled = ( $have === $total && $total > 0 );
//		$hidden_keep  = ( $fmt_disabled && $fmt_checked ) ? '<input type="hidden" name="attachments[' . $post->ID . '][gen_formats][]" value="' . esc_attr( $fmt_key ) . '">' : '';
//		$formats_html .= '<label style="margin-left:.5em;"><input type="checkbox" name="attachments[' . $post->ID . '][gen_formats][]" value="' . esc_attr( $fmt_key ) . '" ' . checked( $fmt_checked,
//				true, false ) . ' ' . disabled( $fmt_disabled, true,
//				false ) . '> ' . __( 'Generate' ) . '</label>' . ( $fmt_disabled ? '<span class="tg-note"> (всё уже есть)</span>' : '' ) . $hidden_keep . '</div>';
//	}
//	$formats_html .= '</div>';
//	$nonce        = wp_create_nonce( 'theme_gen_now_' . $post->ID );
//	$btn          = '<button type="button" class="button" id="theme-gen-now" data-id="' . $post->ID . '" data-nonce="' . $nonce . '">' . esc_html__( 'Generate now' ) . '</button>';
//
//	$fields['theme_gen_formats'] = [
//		'label' => __( 'Generate formats' ),
//		'input' => 'html',
//		'html'  => $formats_html,
//		'helps' => __( 'Statuses for original and sizes' )
//	];
//	$fields['theme_gen_now']     = [
//		'label' => __( 'Run generation' ),
//		'input' => 'html',
//		'html'  => $btn . '<span id="theme-gen-now__status" style="margin-left:8px;"></span>
//			<script>
//			(function(){
//			  const btn=document.getElementById("theme-gen-now"); if(!btn) return;
//			  btn.addEventListener("click",function(){
//			    const status=document.getElementById("theme-gen-now__status");
//			    const pick=(sel)=>Array.from(document.querySelectorAll(sel)).map(n=>n.value);
//			    const sizes=pick("input[name$=\\"[gen_sizes][]\\"]:checked");
//			    const formats=pick("input[name$=\\"[gen_formats][]\\"]:checked");
//			    status.textContent="В процессе...";
//			    const url=(window.ajaxurl)||"/wp-admin/admin-ajax.php";
//			    const body=new URLSearchParams({action:"theme_generate_now",id:this.dataset.id,_wpnonce:this.dataset.nonce,sizes:JSON.stringify(sizes),formats:JSON.stringify(formats)}).toString();
//			    fetch(url,{method:"POST",headers:{"Content-Type":"application/x-www-form-urlencoded"},body})
//			      .then(r=>r.json()).then(j=>{const ok=j&&j.success; status.textContent=ok?"✓ Готово":("✗ "+((j&&j.data&&j.data.message)||"error")); if(ok) location.reload();})
//			      .catch(()=>{ status.textContent="✗ error"; });
//			  });
//			})();
//			</script>',
//		'helps' => __( 'Generate selected sizes & formats' )
//	];
//
//	return $fields;
//}, 10, 2 );
//
//add_filter( 'attachment_fields_to_save', function ( $post, $attachment ) {
//	if ( isset( $attachment['gen_sizes'] ) ) {
//		update_post_meta( $post['ID'], '_gen_sizes',
//			array_values( array_unique( array_map( 'sanitize_text_field', (array) $attachment['gen_sizes'] ) ) ) );
//	} else {
//		delete_post_meta( $post['ID'], '_gen_sizes' );
//	}
//
//	if ( isset( $attachment['gen_formats'] ) ) {
//		update_post_meta( $post['ID'], '_gen_formats',
//			array_values( array_unique( array_map( 'sanitize_text_field', (array) $attachment['gen_formats'] ) ) ) );
//	} else {
//		delete_post_meta( $post['ID'], '_gen_formats' );
//	}
//
//	return $post;
//}, 10, 2 );
//
//add_action( 'wp_ajax_theme_generate_now', function () {
//	$id = (int) ( $_POST['id'] ?? 0 );
//	check_ajax_referer( 'theme_gen_now_' . $id );
//	if ( ! current_user_can( 'upload_files' ) ) {
//		wp_send_json_error( [ 'message' => 'No capability' ], 403 );
//	}
//	if ( ! $id ) {
//		wp_send_json_error( [ 'message' => 'Bad ID' ], 400 );
//	}
//
//	$sizes   = json_decode( stripslashes( $_POST['sizes'] ?? '[]' ), true );
//	$formats = json_decode( stripslashes( $_POST['formats'] ?? '[]' ), true );
//	if ( ! is_array( $sizes ) || ! $sizes ) {
//		$sizes = (array) get_post_meta( $id, '_gen_sizes', true );
//	}
//	if ( ! is_array( $formats ) || ! $formats ) {
//		$formats = (array) get_post_meta( $id, '_gen_formats', true );
//	}
//
//	$sizes   = array_values( array_intersect( $sizes, array_keys( theme_gen_get_sizes_list() ) ) );
//	$formats = array_values( array_intersect( $formats, array_keys( theme_gen_get_formats_list() ) ) );
//
//	$meta = theme_generate_ensure_sizes( $id, $sizes, true );
//	$meta = theme_generate_alt_formats_for_attachment( $id, $meta, $formats );
//	$meta = theme_strip_unwanted_sizes_from_meta( wp_get_attachment_metadata( $id ) ?: [] );
//	wp_update_attachment_metadata( $id, $meta );
//
//	wp_send_json_success( [ 'sizes' => $sizes, 'formats' => $formats ] );
//} );
//
//add_filter( 'intermediate_image_sizes_advanced', function ( $sizes, $image_meta, $attachment_id = 0 ) {
//	if ( ! $attachment_id ) {
//		return $sizes;
//	}
//	$want = get_post_meta( $attachment_id, '_gen_sizes', true );
//
//	return ( empty( $want ) || ! is_array( $want ) ) ? [] : array_intersect_key( $sizes, array_flip( $want ) );
//}, 10, 3 );
//
///* =============================================================================
// *  ПОСТЕР ИЗ ВИДЕО (FFmpeg) + ПРИВЯЗКА К ВИДЕО + UI
// * ========================================================================== */
//
//if ( ! defined( 'THEME_POSTER_TIME' ) ) {
//	define( 'THEME_POSTER_TIME', '00:00:01.000' );
//}
//
//function theme_has_exec(): bool {
//	$disabled = array_map( 'trim', explode( ',', (string) ini_get( 'disable_functions' ) ) );
//
//	return function_exists( 'exec' ) && ! in_array( 'exec', $disabled, true );
//}
//
//function theme_find_ffmpeg(): ?string {
//	foreach ( [ '/usr/bin/ffmpeg', '/usr/local/bin/ffmpeg', '/bin/ffmpeg', 'ffmpeg' ] as $p ) {
//		if ( @is_executable( $p ) ) {
//			return $p;
//		}
//	}
//	if ( theme_has_exec() ) {
//		@exec( 'command -v ffmpeg 2>/dev/null', $o, $rc );
//		if ( $rc === 0 && ! empty( $o[0] ) ) {
//			return trim( $o[0] );
//		}
//	}
//
//	return null;
//}
//
//function theme_normalize_timecode( string $raw, string $fallback = '00:00:01.000' ): string {
//	$v = trim( str_replace( ',', '.', $raw ) );
//	if ( $v === '' ) {
//		return $fallback;
//	}
//	if ( preg_match( '~^\d+(?:\.\d+)?$~', $v ) ) {
//		$ms = (int) round( (float) $v * 1000 );
//		$h  = intdiv( $ms, 3600000 );
//		$ms %= 3600000;
//		$m  = intdiv( $ms, 60000 );
//		$ms %= 60000;
//		$s  = intdiv( $ms, 1000 );
//		$ms %= 1000;
//
//		return sprintf( '%02d:%02d:%02d.%03d', $h, $m, $s, $ms );
//	}
//	$p = explode( ':', $v );
//	if ( count( $p ) === 2 || count( $p ) === 3 ) {
//		$h = 0;
//		$m = 0;
//		$s = 0.0;
//		if ( count( $p ) === 3 ) {
//			[ $hh, $mm, $ss ] = $p;
//			$h = (int) $hh;
//			$m = (int) $mm;
//			$s = (float) $ss;
//		} else {
//			[ $mm, $ss ] = $p;
//			$m = (int) $mm;
//			$s = (float) $ss;
//		}
//		if ( $m >= 60 || $s >= 60 ) {
//			return $fallback;
//		}
//		$ms  = (int) round( $s * 1000 );
//		$sec = $h * 3600 + $m * 60 + intdiv( $ms, 1000 );
//		$ms  %= 1000;
//		$h   = intdiv( $sec, 3600 );
//		$m   = intdiv( $sec % 3600, 60 );
//		$s   = $sec % 60;
//
//		return sprintf( '%02d:%02d:%02d.%03d', $h, $m, $s, $ms );
//	}
//
//	return $fallback;
//}
//
//function theme_generate_video_poster_jpg( int $video_id, array $args = [] ): ?array {
//	$a = array_merge( [
//		'time'      => THEME_POSTER_TIME,
//		'quality'   => 2,
//		'max_width' => 1920,
//		'overwrite' => true,
//		'ffmpeg'    => null
//	], $args );
//	if ( strpos( (string) get_post_mime_type( $video_id ), 'video/' ) !== 0 ) {
//		return null;
//	}
//	$path = get_attached_file( $video_id );
//	$url  = wp_get_attachment_url( $video_id );
//	if ( ! $path || ! $url || ! file_exists( $path ) ) {
//		return null;
//	}
//
//	$dir    = trailingslashit( dirname( $path ) );
//	$dirUrl = trailingslashit( dirname( $url ) );
//	$base   = pathinfo( $path, PATHINFO_FILENAME );
//	$out    = $dir . $base . '-poster.jpg';
//	$outUrl = $dirUrl . $base . '-poster.jpg';
//	if ( file_exists( $out ) && empty( $a['overwrite'] ) ) {
//		return [ 'path' => $out, 'url' => $outUrl ];
//	}
//
//	$ff = $a['ffmpeg'] ?: theme_find_ffmpeg();
//	if ( ! $ff || ! theme_has_exec() ) {
//		return null;
//	}
//	$q   = max( 1, min( 31, (int) $a['quality'] ) );
//	$ow  = ! empty( $a['overwrite'] ) ? '-y' : '-n';
//	$vf  = ( ! empty( $a['max_width'] ) && (int) $a['max_width'] > 0 ) ? ' -vf ' . escapeshellarg( 'scale=' . (int) $a['max_width'] . ':-2' ) : '';
//	$cmd = escapeshellcmd( $ff ) . ' ' . $ow . ' -ss ' . escapeshellarg( (string) $a['time'] ) . ' -i ' . escapeshellarg( $path ) . ' -frames:v 1 -q:v ' . $q . $vf . ' ' . escapeshellarg( $out ) . ' 2>&1';
//	@exec( $cmd, $o, $rc );
//	if ( $rc !== 0 || ! file_exists( $out ) ) {
//		return null;
//	}
//	@chmod( $out, 0644 );
//
//	return [ 'path' => $out, 'url' => $outUrl ];
//}
//
//function theme_register_poster_as_attachment( int $video_id, string $posterPath, string $posterUrl ): ?int {
//	$u = wp_upload_dir();
//	if ( ! file_exists( $posterPath ) ) {
//		return null;
//	}
//	$rel       = ltrim( str_replace( trailingslashit( $u['basedir'] ), '', $posterPath ), '/' );
//	$video     = get_post( $video_id );
//	$parent    = ( $video && $video->post_parent ) ? (int) $video->post_parent : 0;
//	$attach_id = wp_insert_attachment( [
//		'post_mime_type' => 'image/jpeg',
//		'post_title'     => get_the_title( $video_id ) . ' — poster',
//		'post_content'   => '',
//		'post_status'    => 'inherit',
//		'post_parent'    => $parent,
//		'guid'           => $posterUrl,
//	], $posterPath );
//	if ( is_wp_error( $attach_id ) || ! $attach_id ) {
//		return null;
//	}
//
//	wp_update_attachment_metadata( $attach_id, [ 'file' => $rel ] );
//	set_post_thumbnail( $video_id, $attach_id );                 // постер → thumbnail видео
//	add_post_meta( $attach_id, '_source_video_id', $video_id );  // обратная связь
//
//	return $attach_id;
//}
//
///* admin-post: кнопка “Сгенерировать постер” */
//add_action( 'admin_post_theme_generate_video_poster', function () {
//	if ( ! current_user_can( 'upload_files' ) ) {
//		wp_die( 'Insufficient permissions' );
//	}
//	$video_id = (int) ( $_REQUEST['attachment_id'] ?? 0 );
//	$nonce    = $_REQUEST['_wpnonce'] ?? '';
//	if ( ! $video_id || ! wp_verify_nonce( $nonce, 'theme_gen_poster_' . $video_id ) ) {
//		wp_die( 'Bad nonce' );
//	}
//
//	$time = theme_normalize_timecode( (string) ( $_REQUEST['time'] ?? '' ), THEME_POSTER_TIME );
//	update_post_meta( $video_id, '_video_poster_time', $time );
//
//	$r   = theme_generate_video_poster_jpg( $video_id,
//		[ 'time' => $time, 'quality' => 2, 'max_width' => 1920, 'overwrite' => true ] );
//	$ok  = 0;
//	$pid = 0;
//	if ( $r && ! empty( $r['path'] ) && ! empty( $r['url'] ) ) {
//		$reg = theme_register_poster_as_attachment( $video_id, $r['path'], $r['url'] );
//		if ( $reg ) {
//			$ok  = 1;
//			$pid = $reg;
//		}
//	}
//	$back = wp_get_referer() ?: admin_url( 'upload.php' );
//	wp_safe_redirect( add_query_arg( [
//		'theme_poster_done' => $ok,
//		'theme_poster_id'   => $pid,
//		'attachment_id'     => $video_id,
//		'time'              => rawurlencode( $time )
//	], $back ) );
//	exit;
//} );
//
///* метабокс на карточке вложения-видео */
//add_action( 'add_meta_boxes_attachment', function () {
//	add_meta_box( 'zotico_video_poster_box', 'Постер из видео', function ( $post ) {
//		if ( strpos( (string) $post->post_mime_type, 'video/' ) !== 0 ) {
//			echo '<p>Доступно только для видео.</p>';
//
//			return;
//		}
//		$pid = (int) get_post_thumbnail_id( $post->ID );
//		if ( $pid && ! get_post_status( $pid ) ) {
//			delete_post_thumbnail( $post->ID );
//			$pid = 0;
//		}
//		$time_val = get_post_meta( $post->ID, '_video_poster_time', true ) ?: THEME_POSTER_TIME;
//
//		$gen = wp_nonce_url( admin_url( 'admin-post.php?action=theme_generate_video_poster&attachment_id=' . $post->ID ),
//			'theme_gen_poster_' . $post->ID );
//		$det = wp_nonce_url( admin_url( 'admin-post.php?action=theme_detach_video_poster&attachment_id=' . $post->ID ),
//			'theme_detach_poster_' . $post->ID );
//		?>
  <!--      <p><label for="theme_poster_time"><strong>Момент кадра</strong> (HH:MM:SS.mmm или сек.)</label><br>-->
  <!--        <input type="text" id="theme_poster_time" class="regular-text" style="max-width:100%"-->
  <!--          value="--><?php //echo esc_attr( $time_val ); ?><!--" placeholder="--><?php //echo esc_attr( THEME_POSTER_TIME ); ?><!--"></p>-->
  <!--      <p>-->
  <!--        <button type="button" class="button button-primary" id="zotico-gen-poster-btn"-->
  <!--          data-base-url="--><?php //echo esc_url( $gen ); ?><!--"-->
  <!--          style="margin-bottom:10px">--><?php //echo $pid ? 'Пересоздать постер JPG' : 'Сгенерировать постер JPG'; ?><!--</button>-->
  <!--		  --><?php //if ( $pid ): ?>
  <!--            <a href="--><?php //echo esc_url( get_edit_post_link( $pid ) ); ?><!--" class="button" style="margin-bottom:10px">Открыть-->
  <!--              постер</a>-->
  <!--            <a href="--><?php //echo esc_url( $det ); ?><!--" class="button button-link-delete">Открепить постер</a>-->
  <!--		  --><?php //endif; ?>
  <!--      </p>-->
  <!--      <p class="description">После создания постера откройте его (изображение) и нажмите вашу кнопку генерации-->
  <!--        форматов/размеров.</p>-->
  <!--      <script>(function () {-->
  <!--          const b = document.getElementById('zotico-gen-poster-btn'), i = document.getElementById('theme_poster_time');-->
  <!--          if (!b || !i) return;-->
  <!--          b.addEventListener('click', function () {-->
  <!--            this.disabled = true;-->
  <!--            location.href = this.dataset.baseUrl + '&time=' + encodeURIComponent(i.value || '');-->
  <!--          });-->
  <!--        })();</script>-->
  <!--		--><?php
//	}, 'attachment', 'side', 'high' );
//} );
//
///* быстрые действия в списке Медиа */
//add_filter( 'media_row_actions', function ( $actions, $post ) {
//	if ( strpos( (string) $post->post_mime_type, 'video/' ) !== 0 ) {
//		return $actions;
//	}
//	$t                                = get_post_meta( $post->ID, '_video_poster_time', true ) ?: THEME_POSTER_TIME;
//	$url                              = wp_nonce_url( admin_url( 'admin-post.php?action=theme_generate_video_poster&attachment_id=' . $post->ID . '&time=' . rawurlencode( $t ) ),
//		'theme_gen_poster_' . $post->ID );
//	$actions['theme_generate_poster'] = '<a href="' . esc_url( $url ) . '">Сгенерировать постер JPG</a>';
//
//	return $actions;
//}, 10, 2 );
//
///* открепить постер (не удаляя файл) */
//add_action( 'admin_post_theme_detach_video_poster', function () {
//	if ( ! current_user_can( 'upload_files' ) ) {
//		wp_die( 'Insufficient permissions' );
//	}
//	$video_id = (int) ( $_GET['attachment_id'] ?? 0 );
//	$nonce    = $_GET['_wpnonce'] ?? '';
//	if ( ! $video_id || ! wp_verify_nonce( $nonce, 'theme_detach_poster_' . $video_id ) ) {
//		wp_die( 'Bad nonce' );
//	}
//	delete_post_thumbnail( $video_id );
//	$back = wp_get_referer() ?: admin_url( 'upload.php' );
//	wp_safe_redirect( add_query_arg( [ 'theme_poster_unlinked' => 1 ], $back ) );
//	exit;
//} );
//
///* синк sizes постера → в мету видео */
//function theme_sync_poster_sizes_to_video( int $poster_id ): bool {
//	$video_id = (int) get_post_meta( $poster_id, '_source_video_id', true );
//	if ( ! $video_id ) {
//		return false;
//	}
//	$meta = wp_get_attachment_metadata( $poster_id );
//	if ( ! is_array( $meta ) ) {
//		return false;
//	}
//
//	$u        = wp_get_upload_dir();
//	$baseurl  = trailingslashit( $u['baseurl'] );
//	$file_rel = $meta['file'] ?? '';
//	$dir_rel  = $file_rel ? trailingslashit( dirname( $file_rel ) ) : '';
//	$dir_url  = $dir_rel ? $baseurl . $dir_rel : '';
//
//	$sizes_map = [];
//	if ( ! empty( $meta['sizes'] ) && is_array( $meta['sizes'] ) ) {
//		foreach ( $meta['sizes'] as $name => $s ) {
//			$f = $s['file'] ?? '';
//			if ( ! $f ) {
//				continue;
//			}
//			$sizes_map[ $name ] = [
//				'url'    => $dir_url . $f,
//				'width'  => (int) ( $s['width'] ?? 0 ),
//				'height' => (int) ( $s['height'] ?? 0 )
//			];
//		}
//	}
//	update_post_meta( $video_id, '_video_poster_sizes', $sizes_map );
//
//	return true;
//}
//
//add_filter( 'wp_update_attachment_metadata', function ( $data, $post_id ) {
//	if ( wp_attachment_is_image( $post_id ) && get_post_meta( $post_id, '_source_video_id', true ) ) {
//		theme_sync_poster_sizes_to_video( (int) $post_id );
//	}
//
//	return $data;
//}, 10, 2 );
//
///* доп. поля в ответе для видео (poster + sizes) */
//add_filter( 'wp_prepare_attachment_for_js', function ( $response, $attachment, $meta ) {
//	if ( empty( $response['mime'] ) || strpos( (string) $response['mime'], 'video/' ) !== 0 ) {
//		return $response;
//	}
//	$video_id  = (int) $attachment->ID;
//	$poster_id = (int) get_post_thumbnail_id( $video_id );
//	if ( ! $poster_id ) {
//		return $response;
//	}
//
//	$response['poster'] = wp_get_attachment_url( $poster_id ) ?: '';
//	$pmeta              = wp_get_attachment_metadata( $poster_id );
//	if ( is_array( $pmeta ) && ! empty( $pmeta['file'] ) ) {
//		$u       = wp_get_upload_dir();
//		$dir_rel = trailingslashit( dirname( $pmeta['file'] ) );
//		$baseurl = trailingslashit( $u['baseurl'] ) . $dir_rel;
//		if ( ! isset( $response['sizes'] ) || ! is_array( $response['sizes'] ) ) {
//			$response['sizes'] = [];
//		}
//		foreach ( (array) ( $pmeta['sizes'] ?? [] ) as $name => $s ) {
//			if ( empty( $s['file'] ) ) {
//				continue;
//			}
//			$response['sizes'][ $name ] = $baseurl . $s['file'];
//			if ( ! empty( $s['width'] ) ) {
//				$response["{$name}-width"] = (int) $s['width'];
//			}
//			if ( ! empty( $s['height'] ) ) {
//				$response["{$name}-height"] = (int) $s['height'];
//			}
//		}
//	}
//
//	return $response;
//}, 20, 3 );
//
///* =============================================================================
// * УДАЛЕНИЕ: картинка → чистим AVIF/WebP + отвязка; видео → удаляем постер
// * ========================================================================== */
//
//add_action( 'delete_attachment', function ( int $attachment_id ) {
//	$meta = wp_get_attachment_metadata( $attachment_id );
//	$mime = get_post_mime_type( $attachment_id );
//
//	// 1) чистим avif/webp (оригинал + сабсайзы)
//	if ( $meta ) {
//		$uploads = wp_get_upload_dir();
//		$basedir = trailingslashit( $uploads['basedir'] );
//		$baseurl = trailingslashit( $uploads['baseurl'] );
//
//		$to_delete = [];
//		if ( ! empty( $meta['sources'] ) ) {
//			foreach ( (array) $meta['sources'] as $fmt_url ) {
//				if ( strpos( (string) $fmt_url, $baseurl ) === 0 ) {
//					$to_delete[] = str_replace( $baseurl, $basedir, (string) $fmt_url );
//				}
//			}
//		}
//		if ( ! empty( $meta['sizes'] ) ) {
//			foreach ( $meta['sizes'] as $s ) {
//				if ( empty( $s['sources'] ) ) {
//					continue;
//				}
//				foreach ( $s['sources'] as $fmt_url ) {
//					if ( strpos( (string) $fmt_url, $baseurl ) === 0 ) {
//						$to_delete[] = str_replace( $baseurl, $basedir, (string) $fmt_url );
//					}
//				}
//			}
//		}
//		if ( ! empty( $meta['file'] ) ) {
//			$orig_abs  = $basedir . ltrim( $meta['file'], '/' );
//			$to_delete = array_merge( $to_delete, theme_guess_variant_paths( $orig_abs ) );
//			if ( ! empty( $meta['sizes'] ) ) {
//				$folder = dirname( $meta['file'] );
//				foreach ( $meta['sizes'] as $s ) {
//					if ( empty( $s['file'] ) ) {
//						continue;
//					}
//					$abs       = $basedir . ( $folder ? $folder . '/' : '' ) . $s['file'];
//					$to_delete = array_merge( $to_delete, theme_guess_variant_paths( $abs ) );
//				}
//			}
//		}
//		$to_delete = array_values( array_unique( array_filter( $to_delete ) ) );
//		foreach ( $to_delete as $abs ) {
//			theme_safe_unlink( $abs );
//		}
//	}
//
//	// 2) удаляемая картинка — отвязать из видео (если была постером)
//	if ( wp_attachment_is_image( $attachment_id ) ) {
//		$linked_video_id = (int) get_post_meta( $attachment_id, '_source_video_id', true );
//		if ( $linked_video_id && (int) get_post_thumbnail_id( $linked_video_id ) === $attachment_id ) {
//			delete_post_thumbnail( $linked_video_id );
//		}
//
//		$videos = get_posts( [
//			'post_type'              => 'attachment',
//			'post_mime_type'         => 'video',
//			'fields'                 => 'ids',
//			'posts_per_page'         => - 1,
//			'meta_key'               => '_thumbnail_id',
//			'meta_value'             => $attachment_id,
//			'no_found_rows'          => true,
//			'update_post_meta_cache' => false,
//			'update_post_term_cache' => false,
//		] );
//		foreach ( $videos as $vid ) {
//			delete_post_thumbnail( (int) $vid );
//		}
//	}
//
//	// 3) удаляемое видео — удалить его постер (attachment)
//	if ( is_string( $mime ) && strpos( $mime, 'video/' ) === 0 ) {
//		$poster_id = (int) get_post_thumbnail_id( $attachment_id );
//		if ( $poster_id ) {
//			wp_delete_attachment( $poster_id, true );
//		}
//	}
//}, 10, 1 );
//
///* страховка: когда WP удаляет базовый файл, подчистим соседние .avif/.webp */
//add_filter( 'wp_delete_file', function ( string $file_abs ) {
//	if ( preg_match( '/\.(avif|webp)$/i', $file_abs ) ) {
//		return $file_abs;
//	}
//	foreach ( theme_guess_variant_paths( $file_abs ) as $v ) {
//		if ( is_file( $v ) ) {
//			@unlink( $v );
//		}
//	}
//
//	return $file_abs;
//}, 10, 1 );
