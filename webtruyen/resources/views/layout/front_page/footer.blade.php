<section class="footer">
    <section class="footer__left">
        <div class="logo">
            <img src="{{ asset('img/page/logo.png') }}" alt="">
        </div>
        <div class="footer__left__contacts">
            Liên hệ với chúng tôi thông qua:
            <span class="contacts__face_book">
                            <a href="https://www.facebook.com/zerolegendary/">
                                <i class="fa-brands fa-facebook"></i>
                            </a>
                        </span>
        </div>
        <div class="copyright">
            Copyright © web truyện
        </div>
    </section>
    <section class="footer__right">
        <ul class="footer__right__links__box">
            @foreach(categoryList() as $category)
                <li class="footer__right__links__box__item"><a href="{{ route('show_categories', $category->slug) }}">{{ $category->name }}</a></li>
            @endforeach
        </ul>
        <p class="footer__right__content">
            Mọi thông tin và hình ảnh trên website đều được sưu tầm trên Internet. Chúng tôi không sở hữu
            hay chịu
            trách nhiệm bất kỳ thông tin nào trên web này. Nếu làm ảnh hưởng đến cá nhân hay tổ chức nào,
            khi được
            yêu cầu, chúng tôi sẽ xem xét và gỡ bỏ ngay lập tức.
        </p>
    </section>
</section>
