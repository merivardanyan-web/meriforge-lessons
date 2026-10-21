// Ապրանքի էջ՝ «Ավելացնել զամբյուղ» ֆորման — JS Դաս 11, 13
// Էջը դեռ ստատիկ է (Նոութբուք Pro 14)։ Առաջադրանք 2-ում այն կկառուցվի ?id=-ով։

const productForm = document.querySelector("#product-form");
const qtyInput = document.querySelector("#qty");

productForm.addEventListener("submit", (e) => {
    e.preventDefault();                                  // մնում ենք էջում — JS Դաս 11
    const qty = Number(qtyInput.value);
    if (Number.isNaN(qty) || qty < 1) {                  // JS Դաս 8, 13
        qtyInput.value = 1;
        return;
    }
    addProduct(Number(productForm.dataset.id), Math.min(qty, MAX_QTY));
});
