<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
                DB::unprepared('
truncate table sight_sub_categories;

insert sight_sub_categories (category, name) values (1,\'Парк\');
insert sight_sub_categories (category, name) values (1,\'Заповідник\');
insert sight_sub_categories (category, name) values (1,\'Вікове дерево\');
insert sight_sub_categories (category, name) values (1,\'Озеро\');
insert sight_sub_categories (category, name) values (1,\'Гейзер\');
insert sight_sub_categories (category, name) values (1,\'Водойма\');
insert sight_sub_categories (category, name) values (1,\'Ставок\');
insert sight_sub_categories (category, name) values (1,\'Затоплений кар\'\'єр\');
insert sight_sub_categories (category, name) values (1,\'Вода\');
insert sight_sub_categories (category, name) values (1,\'Джерело,\');
insert sight_sub_categories (category, name) values (1,\'Колодязь\');
insert sight_sub_categories (category, name) values (1,\'Купель\');
insert sight_sub_categories (category, name) values (1,\'Вершина\');
insert sight_sub_categories (category, name) values (1,\'Гора\');
insert sight_sub_categories (category, name) values (1,\'Хребет\');
insert sight_sub_categories (category, name) values (1,\'Пагорб\');
insert sight_sub_categories (category, name) values (1,\'Каньйон\');
insert sight_sub_categories (category, name) values (1,\'Підземелля\');
insert sight_sub_categories (category, name) values (1,\'Печера\');
insert sight_sub_categories (category, name) values (1,\'Ґрот\');
insert sight_sub_categories (category, name) values (1,\'Тунель\');
insert sight_sub_categories (category, name) values (1,\'Зоопарк\');
insert sight_sub_categories (category, name) values (1,\'Акваріум\');
insert sight_sub_categories (category, name) values (1,\'Водоспад\');
insert sight_sub_categories (category, name) values (1,\'Каскад водоспадів\');
insert sight_sub_categories (category, name) values (1,\'Штучний водоспад\');
insert sight_sub_categories (category, name) values (1,\'ГЕС\');
insert sight_sub_categories (category, name) values (1,\'Гребля\');
insert sight_sub_categories (category, name) values (1,\'кар\'\'єр\');
insert sight_sub_categories (category, name) values (1,\'Гідротехнічна споруда\');
insert sight_sub_categories (category, name) values (1,\'Шлюз\');
insert sight_sub_categories (category, name) values (1,\'Кемпінг\');

insert sight_sub_categories (category, name) values (2,\'Фортеця\');
insert sight_sub_categories (category, name) values (2,\'Форт\');
insert sight_sub_categories (category, name) values (2,\'Замок\');
insert sight_sub_categories (category, name) values (2,\'Садиба\');
insert sight_sub_categories (category, name) values (2,\'Історична споруда\');
insert sight_sub_categories (category, name) values (2,\'Будинок\');
insert sight_sub_categories (category, name) values (2,\'Комора\');
insert sight_sub_categories (category, name) values (2,\'Земська школа\');
insert sight_sub_categories (category, name) values (2,\'Земська лікарня\');
insert sight_sub_categories (category, name) values (2,\'Млин вітряний\');
insert sight_sub_categories (category, name) values (2,\'Млин водяний\');
insert sight_sub_categories (category, name) values (2,\'Млин паровий\');
insert sight_sub_categories (category, name) values (2,\'Вежа водонапірна\');
insert sight_sub_categories (category, name) values (2,\'Вежа силосна\');
insert sight_sub_categories (category, name) values (2,\'Ратуша\');
insert sight_sub_categories (category, name) values (2,\'Міст кам\'\'яний\');

insert sight_sub_categories (category, name) values (3,\'Скульптура\');
insert sight_sub_categories (category, name) values (3,\'Ідол\');
insert sight_sub_categories (category, name) values (3,\'Бюст\');
insert sight_sub_categories (category, name) values (3,\'Памятник 1-2 світової війни\');
insert sight_sub_categories (category, name) values (3,\'Військовий об\'\'єкт\');
insert sight_sub_categories (category, name) values (3,\'ДОТ\');
insert sight_sub_categories (category, name) values (3,\'Курган\');
insert sight_sub_categories (category, name) values (3,\'Танк\');
insert sight_sub_categories (category, name) values (3,\'Автомобіль\');
insert sight_sub_categories (category, name) values (3,\'Літак\');
insert sight_sub_categories (category, name) values (3,\'Гармата\');
insert sight_sub_categories (category, name) values (3,\'Екскаватор\');
insert sight_sub_categories (category, name) values (3,\'Трактор\');
insert sight_sub_categories (category, name) values (3,\'Поїзд\');
insert sight_sub_categories (category, name) values (3,\'Трамвай\');
insert sight_sub_categories (category, name) values (3,\'Автобус\');
insert sight_sub_categories (category, name) values (3,\'Тролейбус\');
insert sight_sub_categories (category, name) values (3,\'Покинутий військовий об\'\'єкт\');

insert sight_sub_categories (category, name) values (4,\'Церква\');
insert sight_sub_categories (category, name) values (4,\'Костьол\');
insert sight_sub_categories (category, name) values (4,\'Оборонний храм\');
insert sight_sub_categories (category, name) values (4,\'Молитовний будинок\');
insert sight_sub_categories (category, name) values (4,\'Монастир\');
insert sight_sub_categories (category, name) values (4,\'Церква\');
insert sight_sub_categories (category, name) values (4,\'Храм\');
insert sight_sub_categories (category, name) values (4,\'Синагога\');
insert sight_sub_categories (category, name) values (4,\'Мечеть\');
insert sight_sub_categories (category, name) values (4,\'Кірха\');
insert sight_sub_categories (category, name) values (4,\'Каплиця\');
insert sight_sub_categories (category, name) values (4,\'Склеп\');
insert sight_sub_categories (category, name) values (4,\'Каплиця придорожна\');
insert sight_sub_categories (category, name) values (4,\'Зруйнований храм\');

insert sight_sub_categories (category, name) values (6,\'Прапор\');
insert sight_sub_categories (category, name) values (6,\'Оглядовий майданчик\');
insert sight_sub_categories (category, name) values (6,\'Вид на красу\');
insert sight_sub_categories (category, name) values (6,\'Площа\');
insert sight_sub_categories (category, name) values (6,\'Я 💓\');
insert sight_sub_categories (category, name) values (6,\'Фонтан\');
insert sight_sub_categories (category, name) values (6,\'Сонячний годинник\');
insert sight_sub_categories (category, name) values (6,\'Стела\');
insert sight_sub_categories (category, name) values (6,\'Арка\');
insert sight_sub_categories (category, name) values (6,\'Брама\');
insert sight_sub_categories (category, name) values (6,\'Альтанка\');
insert sight_sub_categories (category, name) values (6,\'Мурал\');
insert sight_sub_categories (category, name) values (6,\'Музей\');
insert sight_sub_categories (category, name) values (6,\'Театр\');
insert sight_sub_categories (category, name) values (6,\'Цирк\');
insert sight_sub_categories (category, name) values (6,\'Підвісний міст\');
insert sight_sub_categories (category, name) values (6,\'Пішоходний міст\');
insert sight_sub_categories (category, name) values (6,\'Понтонний міст\');
insert sight_sub_categories (category, name) values (6,\'Пором\');
insert sight_sub_categories (category, name) values (6,\'Річковий трамвайчик\');

insert sight_sub_categories (category, name) values (7,\'Ринок\');
insert sight_sub_categories (category, name) values (7,\'Магазин\');
insert sight_sub_categories (category, name) values (7,\'Готель\');
insert sight_sub_categories (category, name) values (7,\'Кафе\');
insert sight_sub_categories (category, name) values (7,\'Їдальня\');

insert sight_sub_categories (category, name) values (8,\'Некрополь\');
insert sight_sub_categories (category, name) values (8,\'Гробниця\');
insert sight_sub_categories (category, name) values (8,\'Каплиця\');
insert sight_sub_categories (category, name) values (8,\'Склеп\');
insert sight_sub_categories (category, name) values (8,\'Кладовище\');
insert sight_sub_categories (category, name) values (8,\'Військове кладовище\');
insert sight_sub_categories (category, name) values (8,\'Курган\');
insert sight_sub_categories (category, name) values (8,\'Скіфський табір\');

            ');
    }
}
