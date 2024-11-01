import(common.tpl)

head > title = $product['name']
head > meta[name="keywords"]|content = $this->product['meta_keywords']
head > meta[name="description"]|content = $this->product['meta_description']
[data-v-test-test] =  <?php test_this_plugin() .'  TEST9876' ?>
[data-v-test-test-01] =  $product['stock_quantity']

//body|append = <?php var_dump($this->product);?>
