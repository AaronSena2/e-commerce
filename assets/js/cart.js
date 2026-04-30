document.addEventListener("DOMContentLoaded", function() {
  let addButtons = document.querySelectorAll('.add-to-cart');
  addButtons.forEach(function(btn){
    btn.addEventListener('click', function() {
      let productId = this.getAttribute('data-id');
      fetch('/cart/add.php', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'product_id=' + encodeURIComponent(productId)
      })
      .then(response => response.text())
      .then(text => alert("Product added to cart!"));
    });
  });
});
