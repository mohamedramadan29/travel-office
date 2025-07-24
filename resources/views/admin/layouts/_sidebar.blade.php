  <!-- ////////////////////////////////////////////////////////////////////////////-->
  <div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
      <div class="main-menu-content">
          <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
              <li class="nav-item {{ Route::is('dashboard.welcome') ? 'active' : '' }}"><a
                      href="{{ route('dashboard.welcome') }}"><i class="la la-home"></i><span class="menu-title"
                          data-i18n="nav.dash.main">الرئيسية</span></a>
              </li>
              @can('suppliers')
                  <li class="nav-item {{ Route::is('dashboard.suppliers.*') ? 'active' : '' }}"><a href="#"> <i
                              class="bi bi-buildings"></i> <span class="menu-title" data-i18n="nav.role.main"> الموردين
                          </span><span class="float-right mr-2 badge badge-info badge-pill"> {{ $SuppliersCount }} </span>
                      </a>
                      <ul class="menu-content">
                          <li class="{{ Route::is('dashboard.suppliers.index') ? 'active' : '' }}">
                              <a class="menu-item" href="{{ route('dashboard.suppliers.index') }}"
                                  data-i18n="nav.role.index">
                                  جميع الموردين </a>
                          </li>
                          <li class="{{ Route::is('dashboard.suppliers.create') ? 'active' : '' }}">
                              <a class="menu-item" href="{{ route('dashboard.suppliers.create') }}"
                                  data-i18n="nav.templates.vert.classic_menu"> <i class="la la-plus"></i> <span
                                      class="menu-title"> اضافة مورد جديد </a>
                          </li>
                      </ul>
                  </li>
              @endcan
              @can('clients')
                  <li class="nav-item {{ Route::is('dashboard.clients.*') ? 'active' : '' }}"><a href="#"> <i
                              class="bi bi-people-fill"></i> <span class="menu-title" data-i18n="nav.role.main"> العملاء
                          </span><span class="float-right mr-2 badge badge-info badge-pill"> {{ $ClientsCount }} </span>
                      </a>
                      <ul class="menu-content">
                          <li class="{{ Route::is('dashboard.clients.index') ? 'active' : '' }}">
                              <a class="menu-item" href="{{ route('dashboard.clients.index') }}"
                                  data-i18n="nav.role.index">
                                  جميع العملاء </a>
                          </li>
                          <li class="{{ Route::is('dashboard.clients.create') ? 'active' : '' }}">
                              <a class="menu-item" href="{{ route('dashboard.clients.create') }}"
                                  data-i18n="nav.templates.vert.classic_menu"> <i class="la la-plus"></i> <span
                                      class="menu-title"> اضافة عميل جديد </a>
                          </li>
                      </ul>
                  </li>
              @endcan
              @can('categories')
                  <li class="nav-item {{ Route::is('dashboard.categories.*') ? 'active' : '' }}"><a href="#"> <i
                              class="bi bi-bookmarks-fill"></i> <span class="menu-title" data-i18n="nav.role.main"> الاقسام
                          </span><span class="float-right mr-2 badge badge-info badge-pill"> {{ $CategoriesCount }} </span>
                      </a>
                      <ul class="menu-content">
                          <li class="{{ Route::is('dashboard.categories.index') ? 'active' : '' }}">
                              <a class="menu-item" href="{{ route('dashboard.categories.index') }}"
                                  data-i18n="nav.role.index">
                                  جميع الاقسام </a>
                          </li>
                          <li class="{{ Route::is('dashboard.categories.create') ? 'active' : '' }}">
                              <a class="menu-item" href="{{ route('dashboard.categories.create') }}"
                                  data-i18n="nav.templates.vert.classic_menu"> <i class="la la-plus"></i> <span
                                      class="menu-title"> اضافة قسم جديد </a>
                          </li>
                      </ul>
                  </li>
              @endcan
              @can('roles')
                  <li class="nav-item {{ Route::is('dashboard.roles.*') ? 'active' : '' }}"><a href="#"><i
                              class="bi bi-key-fill"></i><span class="menu-title" data-i18n="nav.role.main"> الصلاحيات
                          </span><span class="float-right mr-2 badge badge-info badge-pill"> {{ $PermissionsCount }}
                          </span></a>
                      <ul class="menu-content">
                          <li class="{{ Route::is('dashboard.roles.index') ? 'active' : '' }}">
                              <a class="menu-item" href="{{ route('dashboard.roles.index') }}" data-i18n="nav.role.index">
                                  جميع الصلاحيات </a>
                          </li>
                          <li class="{{ Route::is('dashboard.roles.create') ? 'active' : '' }}">
                              <a class="menu-item" href="{{ route('dashboard.roles.create') }}"
                                  data-i18n="nav.templates.vert.classic_menu"> <i class="la la-plus"></i> <span
                                      class="menu-title""> اضافة صلاحية </a>
                          </li>
                      </ul>
                  </li>
              @endcan

              @can('admins')
                  <li class="nav-item{{ Route::is('dashboard.admins.*') ? 'active' : '' }}"><a href="#"><i
                              class="bi bi-people"></i><span class="menu-title" data-i18n="nav.users.main"> الموظفين
                          </span><span class="float-right mr-2 badge badge-info badge-pill"> {{ $AdminCount }}
                          </span></a>
                      <ul class="menu-content">
                          <li class="{{ Route::is('dashboard.admins.index') ? 'active' : '' }}">
                              <a class="menu-item" href="{{ route('dashboard.admins.index') }}"
                                  data-i18n="nav.users.user_profile"> الموظفين
                              </a>
                          </li>

                          <li class="{{ Route::is('dashboard.admins.create') ? 'active' : '' }}">
                              <a class="menu-item" href="{{ route('dashboard.admins.create') }}"
                                  data-i18n="nav.users.user_cards"> اضافة موظف </a>
                          </li>
                      </ul>
                  </li>
              @endcan

              @can('safes')
                  <li class="nav-item{{ Route::is('dashboard.safes.*') ? 'active' : '' }}"><a href="#"> <i
                              class="bi bi-box-seam"></i> <span class="menu-title" data-i18n="nav.users.main"> الخزائن
                          </span><span class="float-right mr-2 badge badge-info badge-pill"> {{ $SafesCount }}
                          </span></a>
                      <ul class="menu-content">
                          <li class="{{ Route::is('dashboard.safes.index') ? 'active' : '' }}">
                              <a class="menu-item" href="{{ route('dashboard.safes.index') }}"
                                  data-i18n="nav.users.user_profile"> الخزائن
                              </a>
                          </li>

                          <li class="{{ Route::is('dashboard.safes.create') ? 'active' : '' }}">
                              <a class="menu-item" href="{{ route('dashboard.safes.create') }}"
                                  data-i18n="nav.users.user_cards"> اضافة خزائن </a>
                          </li>
                      </ul>
                  </li>
              @endcan

              @can('purches_invoices')
                  <li class="nav-item{{ Route::is('dashboard.purches_invoices.*') ? 'active' : '' }}"><a href="#">
                          <i class="bi bi-receipt"></i> <span class="menu-title" data-i18n="nav.users.main"> فواتير
                              الشراء
                          </span><span class="float-right mr-2 badge badge-info badge-pill"> {{ $PurchesInvoicesCount }}
                          </span></a>
                      <ul class="menu-content">
                          <li class="{{ Route::is('dashboard.purches_invoices.index') ? 'active' : '' }}">
                              <a class="menu-item" href="{{ route('dashboard.purches_invoices.index') }}"
                                  data-i18n="nav.users.user_profile"> فواتير الشراء
                              </a>
                          </li>
                          @can('official_purches_invoices')
                              <li class="{{ Route::is('dashboard.purches_invoices.type') ? 'active' : '' }}">
                                  <a class="menu-item" href="{{ route('dashboard.purches_invoices.type', 'official') }}"
                                      data-i18n="nav.users.user_profile"> فواتير الشراء الرسمية
                                  </a>
                              </li>
                          @endcan
                          @can('interim_purches_invoices')
                              <li class="{{ Route::is('dashboard.purches_invoices.type') ? 'active' : '' }}">
                                  <a class="menu-item" href="{{ route('dashboard.purches_invoices.type', 'interim') }}"
                                      data-i18n="nav.users.user_profile"> فواتير الشراء المؤقتة
                                  </a>
                              </li>
                          @endcan
                          <li class="{{ Route::is('dashboard.purches_invoices.create') ? 'active' : '' }}">
                              <a class="menu-item" href="{{ route('dashboard.purches_invoices.create') }}"
                                  data-i18n="nav.users.user_cards"> اضافة فاتورة شراء </a>
                          </li>
                      </ul>
                  </li>
              @endcan

              @can('settings')
                  <li class="nav-item{{ Route::is('dashboard.settings.*') ? 'active' : '' }}"><a href="#"><i
                              class="la la-dashboard"></i><span class="menu-title" data-i18n="nav.users.main"> الاعدادات
                          </span></a>
                      <ul class="menu-content">
                          <li class="{{ Route::is('dashboard.settings.index') ? 'active' : '' }}">
                              <a class="menu-item" href="{{ route('dashboard.settings.index') }}"
                                  data-i18n="nav.users.user_profile"> ادارة الاعدادات
                              </a>
                          </li>
                      </ul>
                  </li>
              @endcan
          </ul>
      </div>
  </div>
