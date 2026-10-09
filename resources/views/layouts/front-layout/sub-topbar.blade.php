 <div class="topbar top-gray-1000">
      <div class="container-topbar">
        <div class="menu-topbar-left d-none d-xl-block">
          <ul class="nav-small">
            @foreach ($pageTop as $pageDeatils)
            <li><a class="font-xs" href="{{route('page-details', $pageDeatils->slug)}}">{{$pageDeatils->title}}</a></li>
            @endforeach


          </ul>
        </div>
        <div class="info-topbar text-center d-none d-xl-block"><span class="font-xs color-brand-3"></span><span class="font-sm-bold color-success">  </span></div>
        <div class="menu-topbar-right"><span class="font-xs color-brand-3"></span>
          <div class="dropdown dropdown-language">
            <button class="btn dropdown-toggle" id="dropdownPage" type="button" data-bs-toggle="dropdown" aria-expanded="true" data-bs-display="static"><span class="dropdown-right font-xs color-brand-3"><img src="{{ asset('assets/imgs/template/en.svg')}}" alt="Ecom"> English</span></button>
            <ul class="dropdown-menu dropdown-menu-light" aria-labelledby="dropdownPage" data-bs-popper="static">
              <li><a class="dropdown-item" href="#"><img src="{{ asset('assets/imgs/template/flag-en.svg')}}" alt="Ecom"> English</a></li>
              <li><a class="dropdown-item" href="#"><img src="{{ asset('assets/imgs/template/flag-fr.svg')}}" alt="Ecom"> Français</a></li>
              <li><a class="dropdown-item" href="#"><img src="{{ asset('assets/imgs/template/flag-es.svg')}}" alt="Ecom"> Español</a></li>
              <li><a class="dropdown-item" href="#"><img src="{{ asset('assets/imgs/template/flag-pt.svg')}}" alt="Ecom"> Português</a></li>
              <li><a class="dropdown-item" href="#"><img src="{{ asset('assets/imgs/template/flag-cn.svg')}}" alt="Ecom"> 中国人</a></li>
            </ul>
          </div>

        </div>
      </div>
    </div>
