<footer class="footer">
    <div class="row flex-wrap">
        <div class="col w-40 w-md-100">
            <p class="logo mb-10"><a href="{{ route('home') }}">{{ config('app.name') }}</a></p>
            <p>Տեխնիկայի խանութ Երևանում 2018 թվականից։ Ուսումնական նախագիծ։</p>
        </div>

        <div class="col w-25 w-md-50 w-sm-100">
            <h2 class="footer__title">Բաժիններ</h2>
            <nav aria-label="Ստորին մենյու">
                <ul>
                    <li><a href="{{ route('catalog') }}">Կատալոգ</a></li>
                    <li><a href="{{ route('cart.index') }}">Զամբյուղ</a></li>
                    <li><a href="{{ route('account') }}">Իմ հաշիվը</a></li>
                    <li><a href="{{ route('api.products.index') }}">API</a></li>
                </ul>
            </nav>
        </div>

        <div class="col w-25 w-md-50 w-sm-100" id="contact">
            <h2 class="footer__title">Կապ</h2>
            <ul>
                <li>Երևան, Աբովյան 1</li>
                <li><a href="tel:+37410000000">+374 10 00 00 00</a></li>
                <li><a href="mailto:info@technomarket.example">info@technomarket.example</a></li>
            </ul>
        </div>
    </div>

    <div class="row flex-wrap justify-between footer__bottom">
        <div class="col">© {{ date('Y') }} {{ config('app.name') }}</div>
        <div class="col">Laravel · Բլոկ 3</div>
    </div>
</footer>
