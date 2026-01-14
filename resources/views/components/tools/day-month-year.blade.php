@props(['class' => '', 'label' => null, 'fromYear' => null])

<div class="col-sm-6 {{ $class }} form-group px-1" x-data="{
    // نفس منطق component: date
    date: @entangle($attributes->wire('model')),
    updatedvalue: null,
    oldvalue: null,
    showPicker: false,

    // تهيئة القيم: إن لم توجد قيمة حالية نستخدم أول يوم من fromYear أو تاريخ اليوم
    initializeDate() {
        this.oldvalue = (this.date == null ?
            dayjs(new Date({{ isset($fromYear) ? $fromYear . ',0,01' : '' }})).format('YYYY-MM-DD') :
            this.date);
        this.updatedvalue = this.oldvalue;
    },

    // تعديل التاريخ باليوم/الشهر/السنة
    updateDate(unit, amount) {
        const base = this.updatedvalue ?? this.date ?? dayjs().format('YYYY-MM-DD');
        const next = dayjs(base).add(amount, unit);
        const formatted = next.format('YYYY-MM-DD');
        this.updatedvalue = formatted;
        // تحديث مباشر لقيمة النموذج المرتبطة مع Livewire
        this.date = formatted;
    },
    showDatePicker() {
                if (!this.showPicker && this.date ==null) {
                    this.date = dayjs(new Date({{ isset($fromYear) ? $fromYear . ',0,01' : '' }})).format('YYYY-MM-DD');
                }
            this.showPicker =!this.showPicker;
        },
    hideDatePicker() {
        this.showPicker = false;
    },
    deleteDate() {
        this.date = null;
        this.initializeDate();
        this.showPicker = false;
    }
}" x-init="initializeDate();
// عند حفظ الخاصية من السيرفر نعيد مزامنة القيم المؤقتة
$wire.on('propertysaved', () => {
    // بعد نجاح الحفظ تكون this.date هي القيمة النهائية
    this.oldvalue = this.date;
    this.updatedvalue = this.oldvalue;
});">
    <style>
    .button-change {
        background-color: #bababa;
        color: rgb(97, 82, 82);
        font-size: 20px;
        line-height: 25px;
        border: solid 1px #000000;
        box-shadow: rgba(0, 0, 0, 0.1) 0px 10px 50px;
        width: 100%;
        height: 100%;
        padding: 0;
    }

    .button-change:hover {
        background-color: #212427;
        color: white;
        font-size: 20px;
        line-height: 25px;
    }

    /* th {
            border: solid 1px #000000;
            line-height: 0.8;
            background-color: teal !important;
            color: white !important;
            font-size: 16px;
            font-weight: bold;
        } */
    </style>
    <!-- Label -->
    <label class="form-label text-muted small">
        {{ $label ?? trans($attributes->wire('model')->value) }}
    </label>

    <div class="row">
        <div class="col-sm-12 justify-content-center">
            <div class="col-sm-12">
                <div class="form-control position-relative d-flex align-items-center justify-content-center text-center"
                    style="cursor:pointer;" :class="{ 'text-dark': date === null }" x-on:click="showDatePicker()"
                    x-on:click.outside="hideDatePicker()" role="button" tabindex="0">
                    <i class="fa fa-calendar-days position-absolute top-50 start-0 translate-middle-y ms-2 me-3 text-secondary"
                        style="font-weight: unset;" aria-hidden="true" title="اختر التاريخ"
                        x-on:click.stop="showDatePicker()"></i>
                    <span class="d-block border-0 bg-transparent p-0 shadow-none text-center"
                        x-text="date === null || !dayjs(date).isValid() ? 'YYYY/MM/DD' : dayjs(date).format('YYYY/MM/DD')"></span>
                    <!--delete button-->
                    <i class="fa fa-remove position-absolute top-50 end-0 translate-middle-y ms-2 me-3 text-danger"
                        style="font-weight: unset;" aria-hidden="true" title="حذف التاريخ"
                        x-on:click.stop="deleteDate()"></i>
                    <!-- Dropdown-like overlay picker -->
                    <div x-show="showPicker" x-transition
                        class="position-absolute start-0 w-100 bg-white border border-1 border-secondary rounded shadow p-2 mt-1"
                        style="top: 100%; z-index: 1060;">
                        <table class="table m-0 p-0">
                            <tbody class="table-light">
                                <tr class="h4 font-weight-bold">
                                    <td class="border border-1 border-secondary pb-0 mb-0">
                                        <button type="button"
                                            class="btn btn-block mt-0 mb-2 py-0 fa fa-angle-up button-change"
                                            x-on:click.stop="updateDate('day', 1)"></button>
                                    </td>
                                    <td class="border border-1 border-secondary pb-0 mb-0">
                                        <button type="button"
                                            class="btn btn-block mt-0 mb-2 py-0 fa fa-angle-up button-change"
                                            x-on:click.stop="updateDate('month', 1)"></button>
                                    </td>
                                    <td class="border border-1 border-secondary pb-0 mb-0">
                                        <button type="button"
                                            class="btn btn-block mt-0 mb-2 py-0 fa fa-angle-up button-change"
                                            x-on:click.stop="updateDate('year', 1)"></button>
                                    </td>
                                </tr>
                                <tr class="table-light fw-bold py-0 my-0">
                                    <td class="border border-1 border-secondary border-top-0 py-1 my-0">
                                        <div x-text="dayjs(updatedvalue).format('DD')"></div>
                                    </td>
                                    <td class="border border-1 border-secondary border-top-0 py-1 my-0">
                                        <div x-text="dayjs(updatedvalue).format('MM')"></div>
                                    </td>
                                    <td class="border border-1 border-secondary border-top-0 py-1 my-0">
                                        <div x-text="dayjs(updatedvalue).format('YYYY')"></div>
                                    </td>
                                </tr>
                                <tr class="h4 font-weight-bold">
                                    <td class="border border-1 border-secondary border-top-0 pt-0 mt-0">
                                        <button type="button"
                                            class="btn btn-block mt-2 mb-0 py-0 fa fa-angle-down button-change"
                                            x-on:click.stop="updateDate('day', -1)"></button>
                                    </td>
                                    <td class="border border-1 border-secondary border-top-0 pt-0 mt-0">
                                        <button type="button"
                                            class="btn btn-block mt-2 mb-0 py-0 fa fa-angle-down button-change"
                                            x-on:click.stop="updateDate('month', -1)"></button>
                                    </td>
                                    <td class="border border-1 border-secondary border-top-0 pt-0 mt-0">
                                        <button type="button"
                                            class="btn btn-block mt-2 mb-0 py-0 fa fa-angle-down button-change"
                                            x-on:click.stop="updateDate('year', -1)"></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @error($attributes->wire('model')->value)
        <div class="alert alert-danger my-0 rounded-0 rounded-bottom py-1" style="font-size: 14px;">
            {{ $message }}
        </div>
        @enderror
        <!-- تم تحويل أداة الاختيار إلى طبقة منسدلة داخل حاوية العرض أعلاه -->
    </div>
</div>
