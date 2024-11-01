function test_function() {
    console.log("test function");

   fetch('/?module=plugins/test-plugin/index&action=json')
      .then(response => response.json())
      .then(data => console.log(data));

}