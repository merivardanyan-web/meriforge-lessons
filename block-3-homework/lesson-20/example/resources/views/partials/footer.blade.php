<footer class="footer">
    <div class="row">
        <div class="col w-100">
            <div class="wrapper footer__bottom flex justify-between align-center flex-wrap gap-10">
                <p>&copy; {{ date('Y') }}&nbsp;{{ config('app.name') }}</p>
                <a href="{{ route('admin.books.index') }}">Ադմին</a>
            </div>
        </div>
    </div>
</footer>
