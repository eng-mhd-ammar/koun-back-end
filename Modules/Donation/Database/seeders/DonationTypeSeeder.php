<?php

namespace Modules\Donation\Database\seeders;

use Illuminate\Database\Seeder;
use Modules\Address\Models\State;
use Modules\Auth\Models\User;
use Modules\Donation\Models\DonationType;

class DonationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
        public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | الغذاء
        |--------------------------------------------------------------------------
        */

        $food = DonationType::create([
            'name' => 'الغذاء',
            'parent_id' => null,
        ]);

        $dryFood = DonationType::create([
            'name' => 'المواد الغذائية الجافة',
            'parent_id' => $food->id,
        ]);

        $grains = DonationType::create([
            'name' => 'الحبوب والبقوليات',
            'parent_id' => $dryFood->id,
        ]);

        foreach ([
            'الأرز',
            'البرغل',
            'العدس',
            'الفاصولياء',
            'الحمص',
            'الفول',
        ] as $name) {
            DonationType::create([
                'name' => $name,
                'parent_id' => $grains->id,
            ]);
        }

        $basicFood = DonationType::create([
            'name' => 'المواد الأساسية',
            'parent_id' => $dryFood->id,
        ]);

        foreach ([
            'الطحين',
            'السكر',
            'الملح',
            'الزيت',
            'السمن',
        ] as $name) {
            DonationType::create([
                'name' => $name,
                'parent_id' => $basicFood->id,
            ]);
        }

        $cannedFood = DonationType::create([
            'name' => 'المعلبات',
            'parent_id' => $food->id,
        ]);

        $cannedMeat = DonationType::create([
            'name' => 'معلبات اللحوم',
            'parent_id' => $cannedFood->id,
        ]);

        foreach ([
            'التونة',
            'السردين',
            'اللحوم المعلبة',
        ] as $name) {
            DonationType::create([
                'name' => $name,
                'parent_id' => $cannedMeat->id,
            ]);
        }

        $cannedVegetables = DonationType::create([
            'name' => 'معلبات الخضروات',
            'parent_id' => $cannedFood->id,
        ]);

        foreach ([
            'الفاصولياء المعلبة',
            'الذرة المعلبة',
            'البازلاء المعلبة',
        ] as $name) {
            DonationType::create([
                'name' => $name,
                'parent_id' => $cannedVegetables->id,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | الملابس
        |--------------------------------------------------------------------------
        */

        $clothes = DonationType::create([
            'name' => 'الملابس',
            'parent_id' => null,
        ]);

        $menClothes = DonationType::create([
            'name' => 'ملابس رجالية',
            'parent_id' => $clothes->id,
        ]);

        $menSummer = DonationType::create([
            'name' => 'ملابس صيفية',
            'parent_id' => $menClothes->id,
        ]);

        foreach ([
            'قمصان',
            'بناطيل',
            'تيشيرتات',
        ] as $name) {
            DonationType::create([
                'name' => $name,
                'parent_id' => $menSummer->id,
            ]);
        }

        $menWinter = DonationType::create([
            'name' => 'ملابس شتوية',
            'parent_id' => $menClothes->id,
        ]);

        foreach ([
            'معاطف',
            'كنزات',
            'سترات',
        ] as $name) {
            DonationType::create([
                'name' => $name,
                'parent_id' => $menWinter->id,
            ]);
        }

        $womenClothes = DonationType::create([
            'name' => 'ملابس نسائية',
            'parent_id' => $clothes->id,
        ]);

        $womenSummer = DonationType::create([
            'name' => 'ملابس صيفية',
            'parent_id' => $womenClothes->id,
        ]);

        foreach ([
            'فساتين',
            'قمصان',
            'بناطيل',
        ] as $name) {
            DonationType::create([
                'name' => $name,
                'parent_id' => $womenSummer->id,
            ]);
        }

        $womenWinter = DonationType::create([
            'name' => 'ملابس شتوية',
            'parent_id' => $womenClothes->id,
        ]);

        foreach ([
            'معاطف',
            'كنزات',
            'سترات',
        ] as $name) {
            DonationType::create([
                'name' => $name,
                'parent_id' => $womenWinter->id,
            ]);
        }

        $childrenClothes = DonationType::create([
            'name' => 'ملابس أطفال',
            'parent_id' => $clothes->id,
        ]);

        $boysClothes = DonationType::create([
            'name' => 'ملابس أولاد',
            'parent_id' => $childrenClothes->id,
        ]);

        foreach ([
            'ملابس صيفية',
            'ملابس شتوية',
            'أحذية',
        ] as $name) {
            DonationType::create([
                'name' => $name,
                'parent_id' => $boysClothes->id,
            ]);
        }

        $girlsClothes = DonationType::create([
            'name' => 'ملابس بنات',
            'parent_id' => $childrenClothes->id,
        ]);

        foreach ([
            'ملابس صيفية',
            'ملابس شتوية',
            'أحذية',
        ] as $name) {
            DonationType::create([
                'name' => $name,
                'parent_id' => $girlsClothes->id,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | الأثاث
        |--------------------------------------------------------------------------
        */

        $furniture = DonationType::create([
            'name' => 'الأثاث',
            'parent_id' => null,
        ]);

        $bedroom = DonationType::create([
            'name' => 'غرف النوم',
            'parent_id' => $furniture->id,
        ]);

        $beds = DonationType::create([
            'name' => 'الأسرة',
            'parent_id' => $bedroom->id,
        ]);

        foreach ([
            'سرير مفرد',
            'سرير مزدوج',
            'سرير أطفال',
        ] as $name) {
            DonationType::create([
                'name' => $name,
                'parent_id' => $beds->id,
            ]);
        }

        $storage = DonationType::create([
            'name' => 'خزائن التخزين',
            'parent_id' => $bedroom->id,
        ]);

        foreach ([
            'خزانة ملابس',
            'خزانة صغيرة',
            'كومدينة',
        ] as $name) {
            DonationType::create([
                'name' => $name,
                'parent_id' => $storage->id,
            ]);
        }

        $livingRoom = DonationType::create([
            'name' => 'غرف المعيشة',
            'parent_id' => $furniture->id,
        ]);

        $seating = DonationType::create([
            'name' => 'أثاث الجلوس',
            'parent_id' => $livingRoom->id,
        ]);

        foreach ([
            'أريكة',
            'كرسي',
            'طقم جلوس',
        ] as $name) {
            DonationType::create([
                'name' => $name,
                'parent_id' => $seating->id,
            ]);
        }

        $tables = DonationType::create([
            'name' => 'الطاولات',
            'parent_id' => $livingRoom->id,
        ]);

        foreach ([
            'طاولة طعام',
            'طاولة قهوة',
            'طاولة صغيرة',
        ] as $name) {
            DonationType::create([
                'name' => $name,
                'parent_id' => $tables->id,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | الأجهزة والإلكترونيات
        |--------------------------------------------------------------------------
        */

        $electronics = DonationType::create([
            'name' => 'الأجهزة والإلكترونيات',
            'parent_id' => null,
        ]);

        $homeAppliances = DonationType::create([
            'name' => 'الأجهزة المنزلية',
            'parent_id' => $electronics->id,
        ]);

        $kitchenAppliances = DonationType::create([
            'name' => 'أجهزة المطبخ',
            'parent_id' => $homeAppliances->id,
        ]);

        foreach ([
            'ثلاجة',
            'غسالة',
            'فرن',
            'ميكروويف',
        ] as $name) {
            DonationType::create([
                'name' => $name,
                'parent_id' => $kitchenAppliances->id,
            ]);
        }

        $computers = DonationType::create([
            'name' => 'أجهزة الحاسوب',
            'parent_id' => $electronics->id,
        ]);

        $computerTypes = DonationType::create([
            'name' => 'الحواسيب',
            'parent_id' => $computers->id,
        ]);

        foreach ([
            'حاسوب مكتبي',
            'حاسوب محمول',
            'جهاز لوحي',
        ] as $name) {
            DonationType::create([
                'name' => $name,
                'parent_id' => $computerTypes->id,
            ]);
        }

        $phones = DonationType::create([
            'name' => 'الهواتف',
            'parent_id' => $electronics->id,
        ]);

        $phoneTypes = DonationType::create([
            'name' => 'أنواع الهواتف',
            'parent_id' => $phones->id,
        ]);

        foreach ([
            'هاتف ذكي',
            'هاتف عادي',
        ] as $name) {
            DonationType::create([
                'name' => $name,
                'parent_id' => $phoneTypes->id,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | الأدوية والمستلزمات الطبية
        |--------------------------------------------------------------------------
        */

        $medical = DonationType::create([
            'name' => 'الأدوية والمستلزمات الطبية',
            'parent_id' => null,
        ]);

        $medicines = DonationType::create([
            'name' => 'الأدوية',
            'parent_id' => $medical->id,
        ]);

        $medicineTypes = DonationType::create([
            'name' => 'الأدوية الأساسية',
            'parent_id' => $medicines->id,
        ]);

        foreach ([
            'مسكنات الألم',
            'خافضات الحرارة',
            'أدوية الزكام',
            'الفيتامينات',
        ] as $name) {
            DonationType::create([
                'name' => $name,
                'parent_id' => $medicineTypes->id,
            ]);
        }

        $medicalSupplies = DonationType::create([
            'name' => 'المستلزمات الطبية',
            'parent_id' => $medical->id,
        ]);

        $firstAid = DonationType::create([
            'name' => 'مستلزمات الإسعافات الأولية',
            'parent_id' => $medicalSupplies->id,
        ]);

        foreach ([
            'شاش طبي',
            'ضمادات',
            'معقمات',
            'قفازات طبية',
        ] as $name) {
            DonationType::create([
                'name' => $name,
                'parent_id' => $firstAid->id,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | الكتب والقرطاسية
        |--------------------------------------------------------------------------
        */

        $education = DonationType::create([
            'name' => 'الكتب والقرطاسية',
            'parent_id' => null,
        ]);

        $books = DonationType::create([
            'name' => 'الكتب',
            'parent_id' => $education->id,
        ]);

        $educationalBooks = DonationType::create([
            'name' => 'الكتب التعليمية',
            'parent_id' => $books->id,
        ]);

        foreach ([
            'كتب مدرسية',
            'كتب جامعية',
            'كتب تعليمية',
        ] as $name) {
            DonationType::create([
                'name' => $name,
                'parent_id' => $educationalBooks->id,
            ]);
        }

        $stationery = DonationType::create([
            'name' => 'القرطاسية',
            'parent_id' => $education->id,
        ]);

        $schoolSupplies = DonationType::create([
            'name' => 'المستلزمات المدرسية',
            'parent_id' => $stationery->id,
        ]);

        foreach ([
            'دفاتر',
            'أقلام',
            'حقائب مدرسية',
            'أدوات هندسية',
        ] as $name) {
            DonationType::create([
                'name' => $name,
                'parent_id' => $schoolSupplies->id,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | المفروشات
        |--------------------------------------------------------------------------
        */

        $furnishings = DonationType::create([
            'name' => 'المفروشات',
            'parent_id' => null,
        ]);

        $bedding = DonationType::create([
            'name' => 'مفروشات النوم',
            'parent_id' => $furnishings->id,
        ]);

        $blankets = DonationType::create([
            'name' => 'البطانيات والأغطية',
            'parent_id' => $bedding->id,
        ]);

        foreach ([
            'بطانيات',
            'أغطية',
            'لحف',
            'شراشف',
        ] as $name) {
            DonationType::create([
                'name' => $name,
                'parent_id' => $blankets->id,
            ]);
        }

        $carpets = DonationType::create([
            'name' => 'السجاد',
            'parent_id' => $furnishings->id,
        ]);

        $carpetTypes = DonationType::create([
            'name' => 'أنواع السجاد',
            'parent_id' => $carpets->id,
        ]);

        foreach ([
            'سجاد منزلي',
            'سجاد غرف',
            'سجاد صلاة',
        ] as $name) {
            DonationType::create([
                'name' => $name,
                'parent_id' => $carpetTypes->id,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | المستلزمات الشخصية
        |--------------------------------------------------------------------------
        */

        $personal = DonationType::create([
            'name' => 'المستلزمات الشخصية',
            'parent_id' => null,
        ]);

        $hygiene = DonationType::create([
            'name' => 'مستلزمات النظافة',
            'parent_id' => $personal->id,
        ]);

        $personalHygiene = DonationType::create([
            'name' => 'النظافة الشخصية',
            'parent_id' => $hygiene->id,
        ]);

        foreach ([
            'صابون',
            'شامبو',
            'معجون أسنان',
            'فرشاة أسنان',
        ] as $name) {
            DonationType::create([
                'name' => $name,
                'parent_id' => $personalHygiene->id,
            ]);
        }

        $babySupplies = DonationType::create([
            'name' => 'مستلزمات الأطفال',
            'parent_id' => $personal->id,
        ]);

        $babyCare = DonationType::create([
            'name' => 'العناية بالرضع',
            'parent_id' => $babySupplies->id,
        ]);

        foreach ([
            'حفاضات',
            'حليب أطفال',
            'مناديل مبللة',
            'مستلزمات الرضاعة',
        ] as $name) {
            DonationType::create([
                'name' => $name,
                'parent_id' => $babyCare->id,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | المستلزمات المنزلية
        |--------------------------------------------------------------------------
        */

        $household = DonationType::create([
            'name' => 'المستلزمات المنزلية',
            'parent_id' => null,
        ]);

        $kitchen = DonationType::create([
            'name' => 'مستلزمات المطبخ',
            'parent_id' => $household->id,
        ]);

        $kitchenTools = DonationType::create([
            'name' => 'أدوات المطبخ',
            'parent_id' => $kitchen->id,
        ]);

        foreach ([
            'أواني الطبخ',
            'أطباق',
            'أكواب',
            'ملاعق وشوك',
        ] as $name) {
            DonationType::create([
                'name' => $name,
                'parent_id' => $kitchenTools->id,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | التبرعات المالية
        |--------------------------------------------------------------------------
        */

        $financial = DonationType::create([
            'name' => 'التبرعات المالية',
            'parent_id' => null,
        ]);

        $financialGeneral = DonationType::create([
            'name' => 'التبرع المالي العام',
            'parent_id' => $financial->id,
        ]);

        $financialGeneralPurpose = DonationType::create([
            'name' => 'حسب الغرض',
            'parent_id' => $financialGeneral->id,
        ]);

        foreach ([
            'مساعدة الأسر المحتاجة',
            'دعم التعليم',
            'دعم العلاج',
            'الإغاثة الطارئة',
        ] as $name) {
            DonationType::create([
                'name' => $name,
                'parent_id' => $financialGeneralPurpose->id,
            ]);
        }
    }
}
