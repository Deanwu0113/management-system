@php
  use Illuminate\Support\Facades\Route;
  $configData = Helper::appClasses();

  // 获取菜单数据
  $menuItems = $menuData[0]->menu;

  // 找到Logistics菜单项
  $logisticsMenu = null;
  foreach ($menuItems as $key => $menu) {
    if (isset($menu->name) && $menu->name === 'Logistics') {
      $logisticsMenu = $menu;
      unset($menuItems[$key]);
      break;
    }
  }

  // 将Logistics菜单项移到数组开头
  if ($logisticsMenu) {
    array_unshift($menuItems, $logisticsMenu);
  }

  // 过滤菜单项
  $filteredMenu = collect($menuItems)->filter(function ($menu) {
    // 移除不需要的菜单项
    if (isset($menu->name) && in_array($menu->name, [
      'Dashboards', 'Layouts', 'Front Pages', 'Laravel Example', 'eCommerce',
      'Academy', 'Wizard Examples', 'Modal Examples', 'Cards', 'User interface',
      'Extended UI', 'Icons', 'Form Elements', 'Form Layouts', 'Form Wizard',
      'Form Validation', 'Tables', 'Datatables', 'Support', 'Documentation', 'Charts', 'Pages', 'Roles & Permissions'
    ])) {
      return false;
    }

    // 移除特定的menuHeader
    if (isset($menu->menuHeader) && in_array($menu->menuHeader, ['Components', 'Forms & Tables', 'Charts & Maps', 'Apps & Pages', 'Misc'])) {
      return false;
    }

    return true;
  })->values()->all();

  // 过滤掉 Users 菜单的 View 子菜单
  foreach ($filteredMenu as $menu) {
    if (isset($menu->name) && $menu->name === 'Users' && isset($menu->submenu)) {
      $menu->submenu = array_filter($menu->submenu, function ($submenu) {
        return $submenu->name !== 'View';
      });
    }
  }
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

  @if(!isset($navbarFull))
    <div class="app-brand demo">
      <a href="{{url('/app/logistics/dashboard')}}" class="app-brand-link">
        <span class="app-brand-logo demo">@include('_partials.macros',["height"=>20])</span>
        <span class="app-brand-text demo menu-text fw-bold">{{config('variables.templateName')}}</span>
      </a>

      <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
        <i class="ti menu-toggle-icon d-none d-xl-block align-middle"></i>
        <i class="ti ti-x d-block d-xl-none ti-md align-middle"></i>
      </a>
    </div>
  @endif

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    @foreach ($filteredMenu as $menu)

      {{-- adding active and open class if child is active --}}

      {{-- menu headers --}}
      @if (isset($menu->menuHeader))
        <li class="menu-header small">
            <span class="menu-header-text">{{ __($menu->menuHeader) }}</span>
        </li>
      @else

      {{-- active menu method --}}
      @php
      $activeClass = null;
      $currentRouteName = Route::currentRouteName();

      if ($currentRouteName === $menu->slug) {
        $activeClass = 'active';
      }
      elseif (isset($menu->submenu)) {
        if (gettype($menu->slug) === 'array') {
          foreach($menu->slug as $slug){
            if (str_contains($currentRouteName,$slug) and strpos($currentRouteName,$slug) === 0) {
              $activeClass = 'active open';
            }
          }
        }
        else{
          if (str_contains($currentRouteName,$menu->slug) and strpos($currentRouteName,$menu->slug) === 0) {
            $activeClass = 'active open';
          }
        }
      }
      @endphp

      {{-- main menu --}}
      <li class="menu-item {{$activeClass}}">
        <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}" class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}" @if (isset($menu->target) and !empty($menu->target)) target="_blank" @endif>
          @isset($menu->icon)
            <i class="{{ $menu->icon }}"></i>
          @endisset
          <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>
        </a>

        {{-- submenu --}}
        @isset($menu->submenu)
          @include('layouts.sections.menu.submenu',['menu' => $menu->submenu])
        @endisset
      </li>
      @endif

    @endforeach
  </ul>

</aside>
