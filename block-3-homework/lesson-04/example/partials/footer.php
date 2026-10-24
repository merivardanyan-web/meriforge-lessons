    <footer class="footer">
        <div class="row">
            <div class="col w-100">
                <div class="wrapper footer__bottom flex justify-between align-center flex-wrap gap-10">
                    <p>&copy; <?= $year ?>&nbsp;<?= $siteName ?></p>
                    <address><a href="mailto:<?= $email ?>"><?= $email ?></a> · <a href="tel:<?= str_replace(' ', '', $phone) ?>"><?= $phone ?></a> · <?= $city ?></address>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
