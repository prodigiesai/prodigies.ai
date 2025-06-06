<?php
/*
* SiteSEO
* https://siteseo.io/
* (c) SiteSEO Team <support@siteseo.io>
*/

/*
Copyright 2016 - 2024 - Benjamin Denis  (email : contact@seopress.org)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

namespace SiteSEO\Services\ContentAnalysis;

defined('ABSPATH') or exit('Cheatin&#8217; uh?');

class DomAnalysis
{
	protected function getMatches($content, $targetKeywords)
	{
		$data = [];
		foreach ($targetKeywords as $kw) {
			if (preg_match_all('#\b(' . $kw . ')\b#iu', $content, $m)) {
				$data[$kw][] = $m[0];
			}
		}

		if (empty($data)) {
			return null;
		}

		return $data;
	}

	public function getPostContentAnalyze($id)
	{
		//Get post content (used for Words counter)
		$content = get_post_field('post_content', $id);

		//Cornerstone compatibility
		if (is_plugin_active('cornerstone/cornerstone.php')) {
			$content = get_post_field('post_content', $id);
		}

		//ThriveBuilder compatibility
		if (is_plugin_active('thrive-visual-editor/thrive-visual-editor.php') && empty($content)) {
			$content = get_post_meta($id, 'tve_updated_post', true);
		}

		 //Zion Builder compatibility
		 if (is_plugin_active('zionbuilder/zionbuilder.php')) {
			$content = $content . get_post_meta($id, '_zionbuilder_page_elements', true);
		}

		//BeTheme is activated
		$theme = wp_get_theme();
		if ('betheme' == $theme->template || 'Betheme' == $theme->parent_theme) {
			$content = $content . get_post_meta($id, 'mfn-page-items-seo', true);
		}

		//Themify compatibility
		if (defined('THEMIFY_DIR') && method_exists('ThemifyBuilder_Data_Manager', '_get_all_builder_text_content')) {
			global $ThemifyBuilder;
			$builder_data = $ThemifyBuilder->get_builder_data($id);
			$plain_text   = ThemifyBuilder_Data_Manager::_get_all_builder_text_content($builder_data);
			$plain_text   = do_shortcode($plain_text);

			if ('' != $plain_text) {
				$content = $plain_text;
			}
		}

		$post = get_post($id);

		//Add WC product excerpt
		if ('product' == $post->post_type) {
			$content =  $content . get_the_excerpt($id);
		}

		$content = apply_filters('siteseo_content_analysis_content', $content, $id);

		if (defined('BRICKS_DB_EDITOR_MODE') && ('bricks' == $theme->template || 'Bricks' == $theme->parent_theme)) {
			$page_sections = get_post_meta($id, BRICKS_DB_PAGE_CONTENT, true);
			$editor_mode   = get_post_meta($id, BRICKS_DB_EDITOR_MODE, true);
			if (is_array($page_sections) && 'wordpress' !== $editor_mode) {
				$content = \Bricks\Frontend::render_data($page_sections);
			}
		}

		return $content;
	}

	public function getDataAnalyze($data, $options)
	{
		if (!isset($options['id'])) {
			return $data;
		}


		$post = get_post($options['id']);

		$targetKeywords = isset($options['target_keywords']) && !empty($options['target_keywords']) ? $options['target_keywords'] : get_post_meta($options['id'], '_siteseo_analysis_target_kw', true);

		$targetKeywords = array_filter(explode(',', strtolower($targetKeywords)));

		$targetKeywords = apply_filters( 'siteseo_content_analysis_target_keywords', $targetKeywords, $options['id'] );

		//Manage keywords with special characters
		foreach ($targetKeywords as $key => $kw) {
			$kw			   = str_replace('-', ' ', $kw); //remove dashes
			$targetKeywords[$key] = trim(htmlspecialchars_decode($kw, ENT_QUOTES));
		}

		//Remove duplicates
		$targetKeywords = array_unique($targetKeywords);


		$keysAnalyze = [
			"title",
			"description",
			"h1",
			"h2",
			"h3",
		];

		foreach ($keysAnalyze as $value) {
			if (!isset($data[$value]) || !isset($data[$value]['value'])) {
				continue;
			}
			$data[$value]['matches'] = [];

			$items = $data[$value]['value'];
			if (is_string($items)) {
				$matches = $this->getMatches($items, $targetKeywords);
				if ($matches !== null) {
					$keys = array_keys($matches);

					foreach ($keys as $keyMatch => $valueMatch) {
						$data[$value]['matches'][]= [
							"key" => $valueMatch,
							"count" => count($matches[$valueMatch][0])
						];
					}
				}
			} elseif (is_array($items)) {
				foreach ($items as $key => $item) {
					$matches = $this->getMatches($item, $targetKeywords);
					if ($matches !== null) {
						$keys = array_keys($matches);
						foreach ($keys as $keyMatch => $valueMatch) {
							$data[$value]['matches'][]= [
								"key" => $valueMatch,
								"count" => count($matches[$valueMatch][0])
							];
						}
					}
				}
			}
		}

		$postContent = apply_filters('siteseo_dom_analysis_get_post_content', $this->getPostContentAnalyze($options['id']));

		if (defined('WP_DEBUG') && WP_DEBUG) {
			$data['analyzed_content'] = $postContent;
			$data['analyzed_content_id'] = $options['id'];
		}

		//Keywords density
		$data['kws_density'] = [
			"matches" => []
		];
		if (! is_plugin_active('oxygen/functions.php') && ! function_exists('ct_template_output')) { //disable for Oxygen
			$matches = $this->getMatches($postContent, $targetKeywords);
			if ($matches !== null) {
				$keys = array_keys($matches);

				foreach ($keys as $key => $value) {
					$data['kws_density']['matches'][]= [
						"key" => $value,
						"count" => count($matches[$value][0])
					];
				}
			}
		}


		//Words Counter
		if (! is_plugin_active('oxygen/functions.php') && ! function_exists('ct_template_output')) { //disable for Oxygen
			if (!empty($postContent)) {
				$data['words_counter'] = preg_match_all("/\p{L}[\p{L}\p{Mn}\p{Pd}'\x{2019}]*/u", normalize_whitespace(wp_strip_all_tags($postContent)), $matches);

				if (! empty($matches[0])) {
					$wordsCounterUnique = count(array_unique($matches[0]));
				} else {
					$wordsCounterUnique = '0';
				}
				$data['words_counter_unique'] = $wordsCounterUnique;
			}
		}

		//Keywords in permalink
		$slug = urldecode($post->post_name);

		if (is_plugin_active('permalink-manager-pro/permalink-manager.php')) {
			global $permalink_manager_uris;
			$slug = urldecode($permalink_manager_uris[$options['id']]);
		}

		$slug = str_replace('-', ' ', $slug);

		$data['kws_permalink'] = [
			"matches" => []
		];

		if (!empty($targetKeywords)) {
			$matches = $this->getMatches($slug, $targetKeywords);
			if ($matches !== null) {
				$keys = array_keys($matches);
				foreach ($keys as $key => $value) {
					$data['kws_permalink']['matches'][]= [
						"key" => $value,
						"count" => count($matches[$value][0])
					];
				}
			}
		}

		$data['old_post'] = [
			'value' => strtotime($post->post_modified) < strtotime('-365 days')
		];

		//Oxygen builder
		if (is_plugin_active('oxygen/functions.php') && function_exists('ct_template_output')) { //disable for Oxygen
			$dataOxygen = get_post_meta($post->ID, '_siteseo_analysis_data_oxygen', true);


			if ($dataOxygen) {
				if(isset($dataOxygen['words_counter'])){
					$data['words_counter'] = $dataOxygen['words_counter'];
				}
				if(isset($dataOxygen['words_counter_unique'])){
					$data['words_counter_unique'] = $dataOxygen['words_counter_unique'];
				}
				if(isset($dataOxygen['kws_density'])){
					foreach($dataOxygen['kws_density'] as $key => $densities){
						foreach($densities as $keyDensity => $density){
							$data['kws_density']['matches'][]= [
								"key" => $keyDensity,
								"count" => isset($density[0]) ? count($density[0]) : 0
							];
						}

					}
				}
			}
		}

		return $data;
	}
}
