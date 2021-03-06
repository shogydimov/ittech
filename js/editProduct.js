function viewEditProduct() {
    if (self.location == 'http://localhost/ittech/?page=editProduct') {
        if (sessionStorage.editProductObj) {
            var productToEditObj = JSON.parse(sessionStorage.editProductObj);

            var viewProductToEdit = document.getElementById('viewProductToEdit');

            var imageDiv = document.createElement('div');
            imageDiv.className = 'mainImageDiv displayInlineBlock';

            var image = document.createElement('img');
            image.src = "http://localhost/ittech/" + productToEditObj.imgs[0].img_url;
            image.className = 'adminMainImg';
            imageDiv.appendChild(image);
            viewProductToEdit.appendChild(imageDiv);

            var productAtributesDiv = document.createElement('div');
            productAtributesDiv.className = 'adminAtributes';

            var productAtributes = document.createElement('h4');
            productAtributes.innerText = productToEditObj.type + ' ' + productToEditObj.brand + ' ' + productToEditObj.model;
            productAtributesDiv.appendChild(productAtributes);

            var productId = document.createElement('h5');
            productId.id = 'productId';
            productId.value = productToEditObj.product_id;
            productId.innerText = 'Art.№' + productToEditObj.product_id;
            productAtributesDiv.appendChild(productId);
            viewProductToEdit.appendChild(productAtributesDiv);

            var priceDiv = document.createElement('div');
            priceDiv.className = 'adminPriceDiv';

            if (productToEditObj.inPromo) {
                var imgPromo = document.createElement('img');
                imgPromo.className = 'adminPromoImg';
                imgPromo.src = "./assets/displayImages/promo.png";
                imageDiv.appendChild(imgPromo);
                priceDiv.style.color = 'red';
                priceDiv.innerText = 'Promo prise: $' + productToEditObj.price;

            } else {
                priceDiv.innerText = 'Price $' + productToEditObj.price;
            }
            viewProductToEdit.appendChild(priceDiv);

            document.getElementById('currentQuontity').innerText = productToEditObj.quontity;

            var editProductButton = document.getElementById('editProductButton');
            editProductButton.value = productToEditObj.product_id;

        } else {
            var editProductDiv = document.getElementById('editProductDiv');
            editProductDiv.innerHTML = '';
            var a = document.createElement('a');
            a.className = 'link';
            a.href = './';
            a.innerText = 'Select product to change';
            editProductDiv.appendChild(a);
        }
    }
}

function editProduct(productId) {
    sessionStorage.clear();
    var inputQuontity = document.getElementById('newQuontity').value;
    var currentQuontity = document.getElementById('currentQuontity').innerText;

    if (inputQuontity.length > 0 && inputQuontity > 0 && /\d/.test(inputQuontity)) {

        var newQuontity = parseInt(currentQuontity) + parseInt(inputQuontity);

        var editProduct = {
            quontity: newQuontity,
            productId: productId
        };
        var editedProductJson = JSON.stringify(editProduct);

        var request = new XMLHttpRequest();
        request.onreadystatechange = function () {
            if (this.readyState === 4) {
                if (this.status === 200) {

                    var viewProductToEdit = document.getElementById('viewProductToEdit');
                    viewProductToEdit.innerHTML = '';
                    viewProductToEdit.innerText = 'Successfully changed the product quontity';

                    var editProductDiv = document.getElementById('editProductDiv');
                    editProductDiv.innerHTML = '';
                    var allProductsLink = document.createElement('a');
                    allProductsLink.className = 'link';
                    allProductsLink.innerText = 'Select another product';
                    allProductsLink.href = './';
                    editProductDiv.appendChild(allProductsLink);
                }else {
                    window.location.replace('http://localhost/ittech?page=error' + this.status);
                }
            }
        };
        request.open("GET", "http://localhost/ittech/controller/editProductController.php?editProduct=" + editedProductJson);
        request.send();
    }

}