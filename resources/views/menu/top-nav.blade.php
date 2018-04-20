<div class="row">
		<div class="col-md-6 col-sm-8 clearfix">
			<ul class="user-info pull-left pull-none-xsm">
				<li>
					<p class="top-text-small"><em>{{ trans('words.logged-in-as') }}</em></p>
					<p>{{ auth()->user()->name }}</p>
				</li>
			</ul>
		</div>

		<div class="col-md-6 col-sm-4 clearfix hidden-xs">
			<ul class="list-inline links-list pull-right">
				<li class="dropdown language-selector" :class="viewLanguageSwitch ? 'open' : ''" @click="viewLanguageSwitch = !viewLanguageSwitch">
					{{ trans('words.language') }}: &nbsp;
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-close-others="true">
						<img src="{{ asset('img/flags') . '/' . Localization::getCurrentLocale() . '.png' }}" width="16" height="16" />
					</a>
					<ul class="dropdown-menu pull-right">
						<li class="{{ Localization::getCurrentLocale() == 'ar' ? 'active' : '' }}">
							<a href="{{ Localization::getLocalizedURL('ar') }}" data-no-instant>
								<img src="{{ asset('img/flags') . '/ar.png' }}" width="16" height="16" />
								<span>العربية</span>
							</a>
						</li>
						<li class="{{ Localization::getCurrentLocale() == 'en' ? 'active' : '' }}">
							<a href="{{ Localization::getLocalizedURL('en') }}" data-no-instant>
								<img src="{{ asset('img/flags') . '/en.png' }}" width="16" height="16" />
								<span>English</span>
							</a>
						</li>
					</ul>
				</li>
				<li class="sep"></li>
				<li>
					<a href="#"
						role="button"
	                    onclick="event.preventDefault();
	                             document.getElementById('logout-form').submit();">
	                    {{ trans('auth.logout') }} <i class="fa fa-sign-out right" data-no-instant></i>
	                </a>

					<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
						{{ csrf_field() }}
					</form>
				</li>
			</ul>
		</div>
</div>

<hr>
