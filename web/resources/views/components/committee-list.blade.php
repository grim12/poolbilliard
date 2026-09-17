{{--
    <x-committee-list :members />
    - Row list of committee members: avatar photo + name/role on the left, email on the right —
      see the "Výkonný výbor" panel on /sportovni-svaz. Mirrors
      ui/src/_includes/macros/committee-list.njk.
    - members: iterable of CommitteeMember models (or arrays with 'name'/'role'/'email'/
      'photo_url') — photo is optional per member, a placeholder icon shows instead.
--}}
@props([
    'members' => [],
])

@if (count($members))
    <div {{ $attributes->merge(['class' => 'c-committee-list']) }}>
        <div class="c-committee-list__head">
            <span>{{ __('Jméno') }}</span>
            <span>{{ __('E-mail') }}</span>
        </div>
        @foreach ($members as $member)
            <div class="c-committee-list__row">
                <div class="c-committee-list__person">
                    <div class="c-committee-list__photo">
                        @if ($member['photo_url'])
                            <img src="{{ $member['photo_url'] }}" alt="" loading="lazy" />
                        @else
                            <x-heroicon-m-user-circle class="c-committee-list__placeholder" width="24" height="24" />
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="c-committee-list__name">{{ $member['name'] }}</p>
                        @if ($member['role'])
                            <p class="c-committee-list__role">{{ $member['role'] }}</p>
                        @endif
                    </div>
                </div>
                <a class="c-committee-list__email" href="mailto:{{ $member['email'] }}">{{ $member['email'] }}</a>
            </div>
        @endforeach
    </div>
@endif
