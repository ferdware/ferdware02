import(common.tpl)

.settings input[name]|value = <?php 
	$_setting = '@@__name:\[(.*)\]__@@';
	$_default = '@@__value__@@';
	echo $_POST['settings'][$_setting] ?? (Vvveb\getSetting('insert-scripts', $_setting, '', $this->site_id) ?: $_default);
	//name="settings[setting-name] > get only setting-name
?>

.settings textarea = <?php 
	$_setting = '@@__name:\[(.*)\]__@@';
	$_default = '@@__value__@@';
	echo $_POST['settings'][$_setting] ?? (Vvveb\getSetting('insert-scripts', $_setting, '', $this->site_id) ?: $_default);
?>
