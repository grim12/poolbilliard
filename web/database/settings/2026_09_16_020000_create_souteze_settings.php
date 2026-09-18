<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    /**
     * Defaults mirror ui/src/souteze.njk's hardcoded pageHero() call 1:1 — same reasoning as
     * create_kluby_settings.php's own defaults.
     */
    public function up(): void
    {
        $this->migrator->add('souteze.title', 'Systém soutěží');
        $this->migrator->add('souteze.title_en', null);
        $this->migrator->add('souteze.subtitle', 'Od prvního turnaje až k titulu mistra republiky');
        $this->migrator->add('souteze.subtitle_en', null);
        $this->migrator->add(
            'souteze.text',
            'Český soutěžní poolbilliard nabízí příležitosti pro hráče všech výkonnostních úrovní, od začátečníků až po českou špičku. Systém zahrnuje regionální a celostátní turnaje, mistrovské soutěže, juniorské série i ligové soutěže klubových týmů. Každý si tak může najít svou vlastní cestu od prvních turnajových zkušeností až po titul mistra České republiky nebo místo v národní reprezentaci.'
        );
        $this->migrator->add('souteze.text_en', null);

        $this->migrator->add('souteze.stats', [
            ['value' => '6', 'label' => 'soutěžních větví', 'label_en' => null],
            ['value' => '4', 'label' => 'hlavní discipliny', 'label_en' => null],
            ['value' => '3', 'label' => 'ligové úrovně týmů', 'label_en' => null],
            ['value' => '7', 'label' => 'kategorií mistrovství republiky', 'label_en' => null],
        ]);
    }
};
