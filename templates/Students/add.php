<?php
/**
 * @var \App\View\AppView $this
 * @var array $u
 */

$formData = $this->getRequest()->getData();

$selectedFaculty = (string)(
    $formData['faculty'] ?? ''
);

$selectedCourse = (string)(
    $formData['course'] ?? ''
);

$selectedSemester = (string)(
    $formData['semester'] ?? ''
);

$selectedState = (string)(
    $formData['state'] ?? ''
);

$states = [
    'Johor',
    'Kedah',
    'Kelantan',
    'Melaka',
    'Negeri Sembilan',
    'Pahang',
    'Perak',
    'Perlis',
    'Pulau Pinang',
    'Sabah',
    'Sarawak',
    'Selangor',
    'Terengganu',
    'WP Kuala Lumpur',
    'WP Labuan',
    'WP Putrajaya',
];
?>

<div class="space-y-6">
    <div
        class="flex flex-col sm:flex-row
        sm:items-center sm:justify-between gap-4"
    >
        <div>
            <p
                class="text-xs font-bold uppercase
                tracking-widest text-purple-500"
            >
                Admin / Students
            </p>

            <h1
                class="text-2xl font-black
                text-slate-900 dark:text-white mt-1"
            >
                New Student Registration
            </h1>

            <p
                class="text-sm text-slate-500
                dark:text-slate-400 mt-1"
            >
                Create the student login account and
                academic profile.
            </p>
        </div>

        <a
            href="<?= $this->Url->build([
                'controller' => 'Students',
                'action' => 'index',
            ]) ?>"
            class="inline-flex items-center justify-center
            border border-slate-300 dark:border-slate-700
            text-slate-700 dark:text-slate-200
            hover:bg-slate-100 dark:hover:bg-slate-800
            font-bold px-4 py-2 rounded-lg transition"
        >
            ← Student List
        </a>
    </div>

    <div
        class="grid grid-cols-2 md:grid-cols-4
        gap-2"
    >
        <div
            class="registration-step rounded-xl
            border border-purple-500 bg-purple-50
            dark:bg-purple-900/20 p-3"
            data-step-card="1"
        >
            <p class="text-xs text-purple-500">
                Step 1
            </p>

            <p
                class="text-sm font-bold text-purple-700
                dark:text-purple-300"
            >
                Account
            </p>
        </div>

        <div
            class="registration-step rounded-xl
            border border-slate-200
            dark:border-slate-700 p-3"
            data-step-card="2"
        >
            <p class="text-xs text-slate-500">
                Step 2
            </p>

            <p
                class="text-sm font-bold text-slate-700
                dark:text-slate-200"
            >
                Academic
            </p>
        </div>

        <div
            class="registration-step rounded-xl
            border border-slate-200
            dark:border-slate-700 p-3"
            data-step-card="3"
        >
            <p class="text-xs text-slate-500">
                Step 3
            </p>

            <p
                class="text-sm font-bold text-slate-700
                dark:text-slate-200"
            >
                Contact
            </p>
        </div>

        <div
            class="registration-step rounded-xl
            border border-slate-200
            dark:border-slate-700 p-3"
            data-step-card="4"
        >
            <p class="text-xs text-slate-500">
                Step 4
            </p>

            <p
                class="text-sm font-bold text-slate-700
                dark:text-slate-200"
            >
                Resume & Review
            </p>
        </div>
    </div>

    <div class="grid xl:grid-cols-4 gap-6 items-start">
        <div class="space-y-5">
            <div
                class="bg-white dark:bg-slate-800
                rounded-2xl p-5 shadow
                border-l-4 border-purple-500"
            >
                <h2
                    class="font-bold text-slate-900
                    dark:text-white"
                >
                    Registration Guide
                </h2>

                <div
                    class="mt-4 space-y-3 text-sm
                    text-slate-600 dark:text-slate-300"
                >
                    <p>
                        <strong>Matrix number:</strong>
                        Must contain 8–15 digits.
                    </p>

                    <p>
                        <strong>Password:</strong>
                        Minimum 8 characters.
                    </p>

                    <p>
                        <strong>Resume:</strong>
                        PDF only, maximum 5 MB.
                    </p>

                    <p>
                        <strong>QR:</strong>
                        Generated automatically after
                        registration.
                    </p>
                </div>
            </div>

            <div
                class="bg-gradient-to-br from-purple-700
                to-indigo-700 rounded-2xl p-5
                text-white shadow"
            >
                <div
                    id="studentInitial"
                    class="w-20 h-20 mx-auto rounded-full
                    bg-white/15 border border-white/20
                    flex items-center justify-center
                    text-2xl font-black"
                >
                    ST
                </div>

                <p
                    id="studentPreviewName"
                    class="mt-4 text-center font-black"
                >
                    New Student
                </p>

                <p
                    id="studentPreviewMatrix"
                    class="text-center text-sm opacity-75"
                >
                    Matrix Number
                </p>
            </div>
        </div>

        <div
            class="xl:col-span-3 bg-white
            dark:bg-slate-800 rounded-2xl
            shadow overflow-hidden"
        >
            <?= $this->Form->create(null, [
                'type' => 'file',
                'class' => 'student-registration-form',
                'id' => 'studentRegistrationForm',
            ]) ?>

            <div class="p-5 md:p-7 space-y-8">
                <section
                    class="form-section"
                    data-form-section="1"
                >
                    <div
                        class="flex items-center gap-3
                        border-b border-slate-200
                        dark:border-slate-700 pb-3 mb-5"
                    >
                        <span
                            class="w-9 h-9 rounded-full
                            bg-purple-100 text-purple-700
                            dark:bg-purple-900/30
                            dark:text-purple-300
                            flex items-center justify-center
                            font-black"
                        >
                            1
                        </span>

                        <div>
                            <h2
                                class="font-black text-slate-900
                                dark:text-white"
                            >
                                Login Account
                            </h2>

                            <p
                                class="text-xs text-slate-500
                                dark:text-slate-400"
                            >
                                Information used by the student
                                to log in.
                            </p>
                        </div>
                    </div>

                    <div
                        class="grid md:grid-cols-2 gap-5"
                    >
                        <div>
                            <label
                                for="name"
                                class="block text-sm font-bold
                                text-slate-700
                                dark:text-slate-200 mb-2"
                            >
                                Full Name *
                            </label>

                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="<?= h(
                                    (string)(
                                        $formData['name'] ?? ''
                                    )
                                ) ?>"
                                required
                                maxlength="150"
                                autocomplete="name"
                                placeholder="Example: Nur Aisyah"
                                class="w-full rounded-lg px-3 py-3
                                bg-slate-50 dark:bg-slate-900
                                border border-slate-200
                                dark:border-slate-700
                                text-slate-900 dark:text-white
                                focus:outline-none
                                focus:border-purple-500"
                            >
                        </div>

                        <div>
                            <label
                                for="matrix_number"
                                class="block text-sm font-bold
                                text-slate-700
                                dark:text-slate-200 mb-2"
                            >
                                Matrix Number *
                            </label>

                            <input
                                id="matrix_number"
                                type="text"
                                name="matrix_number"
                                value="<?= h(
                                    (string)(
                                        $formData[
                                            'matrix_number'
                                        ] ?? ''
                                    )
                                ) ?>"
                                required
                                minlength="8"
                                maxlength="15"
                                inputmode="numeric"
                                pattern="[0-9]{8,15}"
                                placeholder="Example: 2026115482"
                                class="w-full rounded-lg px-3 py-3
                                bg-slate-50 dark:bg-slate-900
                                border border-slate-200
                                dark:border-slate-700
                                text-slate-900 dark:text-white
                                focus:outline-none
                                focus:border-purple-500"
                            >

                            <p
                                id="matrixMessage"
                                class="text-xs text-slate-500
                                mt-1"
                            >
                                Enter 8–15 digits only.
                            </p>
                        </div>

                        <div>
                            <label
                                for="email"
                                class="block text-sm font-bold
                                text-slate-700
                                dark:text-slate-200 mb-2"
                            >
                                Login Email *
                            </label>

                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="<?= h(
                                    (string)(
                                        $formData['email'] ?? ''
                                    )
                                ) ?>"
                                required
                                autocomplete="email"
                                placeholder="student@email.com"
                                class="w-full rounded-lg px-3 py-3
                                bg-slate-50 dark:bg-slate-900
                                border border-slate-200
                                dark:border-slate-700
                                text-slate-900 dark:text-white
                                focus:outline-none
                                focus:border-purple-500"
                            >

                            <p
                                id="emailMessage"
                                class="text-xs text-slate-500
                                mt-1"
                            >
                                This email will be used to log in.
                            </p>
                        </div>

                        <div>
                            <label
                                for="password"
                                class="block text-sm font-bold
                                text-slate-700
                                dark:text-slate-200 mb-2"
                            >
                                Password *
                            </label>

                            <div class="relative">
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    minlength="8"
                                    autocomplete="new-password"
                                    placeholder="Minimum 8 characters"
                                    class="w-full rounded-lg
                                    px-3 py-3 pr-16
                                    bg-slate-50
                                    dark:bg-slate-900
                                    border border-slate-200
                                    dark:border-slate-700
                                    text-slate-900
                                    dark:text-white
                                    focus:outline-none
                                    focus:border-purple-500"
                                >

                                <button
                                    id="togglePassword"
                                    type="button"
                                    class="absolute right-3 top-1/2
                                    -translate-y-1/2 text-xs
                                    font-bold text-purple-600"
                                >
                                    Show
                                </button>
                            </div>

                            <div
                                class="mt-2 h-2 rounded-full
                                bg-slate-200 dark:bg-slate-700
                                overflow-hidden"
                            >
                                <div
                                    id="passwordStrengthBar"
                                    class="h-full w-0 transition-all"
                                ></div>
                            </div>

                            <p
                                id="passwordStrengthText"
                                class="text-xs text-slate-500
                                mt-1"
                            >
                                Password strength: Not entered
                            </p>
                        </div>
                    </div>
                </section>

                <section
                    class="form-section"
                    data-form-section="2"
                >
                    <div
                        class="flex items-center gap-3
                        border-b border-slate-200
                        dark:border-slate-700 pb-3 mb-5"
                    >
                        <span
                            class="w-9 h-9 rounded-full
                            bg-blue-100 text-blue-700
                            dark:bg-blue-900/30
                            dark:text-blue-300
                            flex items-center justify-center
                            font-black"
                        >
                            2
                        </span>

                        <div>
                            <h2
                                class="font-black text-slate-900
                                dark:text-white"
                            >
                                Academic Information
                            </h2>

                            <p
                                class="text-xs text-slate-500
                                dark:text-slate-400"
                            >
                                Faculty, course and current semester.
                            </p>
                        </div>
                    </div>

                    <div
                        class="grid md:grid-cols-3 gap-5"
                    >
                        <div>
                            <label
                                for="faculty"
                                class="block text-sm font-bold
                                text-slate-700
                                dark:text-slate-200 mb-2"
                            >
                                Faculty *
                            </label>

                            <select
                                id="faculty"
                                name="faculty"
                                required
                                class="w-full rounded-lg px-3 py-3
                                bg-slate-50 dark:bg-slate-900
                                border border-slate-200
                                dark:border-slate-700
                                text-slate-900 dark:text-white
                                focus:outline-none
                                focus:border-purple-500"
                            >
                                <option value="">
                                    Select Faculty
                                </option>

                                <?php foreach ([
                                    'Fakulti Sains Komputer',
                                    'Fakulti Pengurusan Perniagaan',
                                    'Fakulti Kejuruteraan',
                                ] as $faculty): ?>
                                    <option
                                        value="<?= h($faculty) ?>"
                                        <?= $selectedFaculty === $faculty
                                            ? 'selected'
                                            : ''
                                        ?>
                                    >
                                        <?= h($faculty) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label
                                for="course"
                                class="block text-sm font-bold
                                text-slate-700
                                dark:text-slate-200 mb-2"
                            >
                                Course *
                            </label>

                            <select
                                id="course"
                                name="course"
                                required
                                data-selected-course="<?= h(
                                    $selectedCourse
                                ) ?>"
                                class="w-full rounded-lg px-3 py-3
                                bg-slate-50 dark:bg-slate-900
                                border border-slate-200
                                dark:border-slate-700
                                text-slate-900 dark:text-white
                                focus:outline-none
                                focus:border-purple-500"
                            >
                                <option value="">
                                    Select Faculty First
                                </option>
                            </select>
                        </div>

                        <div>
                            <label
                                for="semester"
                                class="block text-sm font-bold
                                text-slate-700
                                dark:text-slate-200 mb-2"
                            >
                                Semester *
                            </label>

                            <select
                                id="semester"
                                name="semester"
                                required
                                class="w-full rounded-lg px-3 py-3
                                bg-slate-50 dark:bg-slate-900
                                border border-slate-200
                                dark:border-slate-700
                                text-slate-900 dark:text-white
                                focus:outline-none
                                focus:border-purple-500"
                            >
                                <option value="">
                                    Select Semester
                                </option>

                                <?php for ($semester = 1; $semester <= 8; $semester++): ?>
                                    <option
                                        value="<?= $semester ?>"
                                        <?= $selectedSemester ===
                                            (string)$semester
                                            ? 'selected'
                                            : ''
                                        ?>
                                    >
                                        Semester <?= $semester ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                </section>

                <section
                    class="form-section"
                    data-form-section="3"
                >
                    <div
                        class="flex items-center gap-3
                        border-b border-slate-200
                        dark:border-slate-700 pb-3 mb-5"
                    >
                        <span
                            class="w-9 h-9 rounded-full
                            bg-emerald-100
                            text-emerald-700
                            dark:bg-emerald-900/30
                            dark:text-emerald-300
                            flex items-center justify-center
                            font-black"
                        >
                            3
                        </span>

                        <div>
                            <h2
                                class="font-black text-slate-900
                                dark:text-white"
                            >
                                Contact Information
                            </h2>

                            <p
                                class="text-xs text-slate-500
                                dark:text-slate-400"
                            >
                                Student phone number and address.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div
                            class="grid md:grid-cols-2 gap-5"
                        >
                            <div>
                                <label
                                    for="phone_number"
                                    class="block text-sm
                                    font-bold text-slate-700
                                    dark:text-slate-200 mb-2"
                                >
                                    Phone Number *
                                </label>

                                <input
                                    id="phone_number"
                                    type="text"
                                    name="phone_number"
                                    value="<?= h(
                                        (string)(
                                            $formData[
                                                'phone_number'
                                            ] ?? ''
                                        )
                                    ) ?>"
                                    required
                                    maxlength="20"
                                    inputmode="tel"
                                    autocomplete="tel"
                                    placeholder="Example: 012-3456789"
                                    class="w-full rounded-lg
                                    px-3 py-3 bg-slate-50
                                    dark:bg-slate-900
                                    border border-slate-200
                                    dark:border-slate-700
                                    text-slate-900
                                    dark:text-white
                                    focus:outline-none
                                    focus:border-purple-500"
                                >
                            </div>

                            <div>
                                <label
                                    for="state"
                                    class="block text-sm
                                    font-bold text-slate-700
                                    dark:text-slate-200 mb-2"
                                >
                                    State
                                </label>

                                <select
                                    id="state"
                                    name="state"
                                    class="w-full rounded-lg
                                    px-3 py-3 bg-slate-50
                                    dark:bg-slate-900
                                    border border-slate-200
                                    dark:border-slate-700
                                    text-slate-900
                                    dark:text-white
                                    focus:outline-none
                                    focus:border-purple-500"
                                >
                                    <?php foreach ($states as $state): ?>
                                        <option
                                            value="<?= h($state) ?>"
                                            <?= $selectedState === $state
                                                ? 'selected'
                                                : ''
                                            ?>
                                        >
                                            <?= h($state) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label
                                for="address"
                                class="block text-sm font-bold
                                text-slate-700
                                dark:text-slate-200 mb-2"
                            >
                                Street Address
                            </label>

                            <input
                                id="address"
                                type="text"
                                name="address"
                                value="<?= h(
                                    (string)(
                                        $formData['address'] ?? ''
                                    )
                                ) ?>"
                                maxlength="255"
                                autocomplete="street-address"
                                placeholder="House number and street"
                                class="w-full rounded-lg px-3 py-3
                                bg-slate-50 dark:bg-slate-900
                                border border-slate-200
                                dark:border-slate-700
                                text-slate-900 dark:text-white
                                focus:outline-none
                                focus:border-purple-500"
                            >
                        </div>

                        <div
                            class="grid md:grid-cols-2 gap-5"
                        >
                            <div>
                                <label
                                    for="city"
                                    class="block text-sm
                                    font-bold text-slate-700
                                    dark:text-slate-200 mb-2"
                                >
                                    City
                                </label>

                                <input
                                    id="city"
                                    type="text"
                                    name="city"
                                    value="<?= h(
                                        (string)(
                                            $formData['city'] ?? ''
                                        )
                                    ) ?>"
                                    maxlength="100"
                                    autocomplete="address-level2"
                                    class="w-full rounded-lg
                                    px-3 py-3 bg-slate-50
                                    dark:bg-slate-900
                                    border border-slate-200
                                    dark:border-slate-700
                                    text-slate-900
                                    dark:text-white
                                    focus:outline-none
                                    focus:border-purple-500"
                                >
                            </div>

                            <div>
                                <label
                                    for="postcode"
                                    class="block text-sm
                                    font-bold text-slate-700
                                    dark:text-slate-200 mb-2"
                                >
                                    Postcode
                                </label>

                                <input
                                    id="postcode"
                                    type="text"
                                    name="postcode"
                                    value="<?= h(
                                        (string)(
                                            $formData[
                                                'postcode'
                                            ] ?? ''
                                        )
                                    ) ?>"
                                    maxlength="10"
                                    inputmode="numeric"
                                    autocomplete="postal-code"
                                    class="w-full rounded-lg
                                    px-3 py-3 bg-slate-50
                                    dark:bg-slate-900
                                    border border-slate-200
                                    dark:border-slate-700
                                    text-slate-900
                                    dark:text-white
                                    focus:outline-none
                                    focus:border-purple-500"
                                >
                            </div>
                        </div>
                    </div>
                </section>

                <section
                    class="form-section"
                    data-form-section="4"
                >
                    <div
                        class="flex items-center gap-3
                        border-b border-slate-200
                        dark:border-slate-700 pb-3 mb-5"
                    >
                        <span
                            class="w-9 h-9 rounded-full
                            bg-amber-100 text-amber-700
                            dark:bg-amber-900/30
                            dark:text-amber-300
                            flex items-center justify-center
                            font-black"
                        >
                            4
                        </span>

                        <div>
                            <h2
                                class="font-black text-slate-900
                                dark:text-white"
                            >
                                Resume
                            </h2>

                            <p
                                class="text-xs text-slate-500
                                dark:text-slate-400"
                            >
                                Resume is optional during registration.
                            </p>
                        </div>
                    </div>

                    <label
                        for="resume_file"
                        id="resumeDropZone"
                        class="block rounded-2xl border-2
                        border-dashed border-slate-300
                        dark:border-slate-700 p-8
                        text-center cursor-pointer
                        hover:border-purple-500
                        hover:bg-purple-50
                        dark:hover:bg-purple-900/10
                        transition"
                    >
                        <div class="text-4xl">
                            📄
                        </div>

                        <p
                            class="mt-3 font-bold text-slate-900
                            dark:text-white"
                        >
                            Upload Student Resume
                        </p>

                        <p
                            id="resumeFileName"
                            class="mt-1 text-sm text-slate-500
                            dark:text-slate-400"
                        >
                            Select one PDF file, maximum 5 MB.
                        </p>

                        <input
                            id="resume_file"
                            type="file"
                            name="resume_file"
                            accept="application/pdf,.pdf"
                            class="hidden"
                        >
                    </label>
                </section>
            </div>

            <div
                class="px-5 md:px-7 py-5
                bg-slate-50 dark:bg-slate-900/50
                border-t border-slate-200
                dark:border-slate-700
                flex flex-col-reverse sm:flex-row
                sm:items-center sm:justify-between gap-3"
            >
                <p
                    class="text-xs text-slate-500
                    dark:text-slate-400"
                >
                    Student will receive role:
                    <strong>STUDENT</strong>
                </p>

                <div class="flex gap-3">
                    <a
                        href="<?= $this->Url->build([
                            'controller' => 'Students',
                            'action' => 'index',
                        ]) ?>"
                        class="inline-flex justify-center
                        border border-slate-300
                        dark:border-slate-700
                        text-slate-700
                        dark:text-slate-200
                        px-5 py-3 rounded-lg font-bold"
                    >
                        Cancel
                    </a>

                    <button
                        id="submitStudentButton"
                        type="submit"
                        class="inline-flex justify-center
                        bg-purple-600 hover:bg-purple-700
                        text-white px-6 py-3 rounded-lg
                        font-bold transition shadow"
                    >
                        Register Student
                    </button>
                </div>
            </div>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const courseOptions = {
        'Fakulti Sains Komputer': [
            'Sistem Maklumat',
            'Sains Komputer',
            'Teknologi Maklumat',
            'Multimedia'
        ],

        'Fakulti Pengurusan Perniagaan': [
            'Pengurusan Perniagaan',
            'Pemasaran',
            'Kewangan',
            'Pengurusan Sumber Manusia'
        ],

        'Fakulti Kejuruteraan': [
            'Kejuruteraan Awam',
            'Kejuruteraan Elektrik',
            'Kejuruteraan Mekanikal',
            'Kejuruteraan Kimia'
        ]
    };

    const facultySelect =
        document.getElementById('faculty');

    const courseSelect =
        document.getElementById('course');

    function updateCourses() {
        const faculty = facultySelect.value;

        const selectedCourse =
            courseSelect.dataset.selectedCourse || '';

        courseSelect.innerHTML =
            '<option value="">Select Course</option>';

        if (!courseOptions[faculty]) {
            courseSelect.innerHTML =
                '<option value="">Select Faculty First</option>';

            return;
        }

        courseOptions[faculty].forEach(function (course) {
            const option = document.createElement('option');

            option.value = course;
            option.textContent = course;

            if (course === selectedCourse) {
                option.selected = true;
            }

            courseSelect.appendChild(option);
        });

        courseSelect.dataset.selectedCourse = '';
    }

    facultySelect.addEventListener(
        'change',
        updateCourses
    );

    updateCourses();

    const nameInput =
        document.getElementById('name');

    const matrixInput =
        document.getElementById('matrix_number');

    const previewName =
        document.getElementById('studentPreviewName');

    const previewMatrix =
        document.getElementById('studentPreviewMatrix');

    const studentInitial =
        document.getElementById('studentInitial');

    function updateStudentPreview() {
        const name = nameInput.value.trim();

        const matrix = matrixInput.value.trim();

        previewName.textContent =
            name || 'New Student';

        previewMatrix.textContent =
            matrix || 'Matrix Number';

        if (name) {
            const words = name
                .split(/\s+/)
                .filter(Boolean);

            const initials = words
                .slice(0, 2)
                .map(function (word) {
                    return word.charAt(0).toUpperCase();
                })
                .join('');

            studentInitial.textContent =
                initials || 'ST';
        } else {
            studentInitial.textContent = 'ST';
        }
    }

    nameInput.addEventListener(
        'input',
        updateStudentPreview
    );

    matrixInput.addEventListener(
        'input',
        function () {
            matrixInput.value =
                matrixInput.value.replace(/\D/g, '');

            updateStudentPreview();

            const matrixMessage =
                document.getElementById(
                    'matrixMessage'
                );

            if (
                matrixInput.value.length >= 8
                && matrixInput.value.length <= 15
            ) {
                matrixMessage.textContent =
                    '✓ Matrix number format is valid.';

                matrixMessage.className =
                    'text-xs text-emerald-600 mt-1';
            } else {
                matrixMessage.textContent =
                    'Enter 8–15 digits only.';

                matrixMessage.className =
                    'text-xs text-slate-500 mt-1';
            }
        }
    );

    updateStudentPreview();

    const passwordInput =
        document.getElementById('password');

    const passwordBar =
        document.getElementById(
            'passwordStrengthBar'
        );

    const passwordText =
        document.getElementById(
            'passwordStrengthText'
        );

    passwordInput.addEventListener(
        'input',
        function () {
            const value = passwordInput.value;

            let score = 0;

            if (value.length >= 8) {
                score++;
            }

            if (/[a-z]/.test(value)) {
                score++;
            }

            if (/[A-Z]/.test(value)) {
                score++;
            }

            if (/[0-9]/.test(value)) {
                score++;
            }

            if (/[^A-Za-z0-9]/.test(value)) {
                score++;
            }

            if (value.length === 0) {
                passwordBar.style.width = '0%';
                passwordBar.className =
                    'h-full w-0 transition-all';

                passwordText.textContent =
                    'Password strength: Not entered';

                passwordText.className =
                    'text-xs text-slate-500 mt-1';

                return;
            }

            if (score <= 2) {
                passwordBar.style.width = '33%';
                passwordBar.className =
                    'h-full transition-all bg-red-500';

                passwordText.textContent =
                    'Password strength: Weak';

                passwordText.className =
                    'text-xs text-red-500 mt-1';
            } else if (score <= 4) {
                passwordBar.style.width = '66%';
                passwordBar.className =
                    'h-full transition-all bg-amber-500';

                passwordText.textContent =
                    'Password strength: Medium';

                passwordText.className =
                    'text-xs text-amber-500 mt-1';
            } else {
                passwordBar.style.width = '100%';
                passwordBar.className =
                    'h-full transition-all bg-emerald-500';

                passwordText.textContent =
                    'Password strength: Strong';

                passwordText.className =
                    'text-xs text-emerald-500 mt-1';
            }
        }
    );

    const togglePassword =
        document.getElementById('togglePassword');

    togglePassword.addEventListener(
        'click',
        function () {
            const isPassword =
                passwordInput.type === 'password';

            passwordInput.type =
                isPassword ? 'text' : 'password';

            togglePassword.textContent =
                isPassword ? 'Hide' : 'Show';
        }
    );

    const phoneInput =
        document.getElementById('phone_number');

    phoneInput.addEventListener(
        'input',
        function () {
            phoneInput.value =
                phoneInput.value.replace(
                    /[^0-9+\-\s]/g,
                    ''
                );
        }
    );

    const resumeInput =
        document.getElementById('resume_file');

    const resumeFileName =
        document.getElementById(
            'resumeFileName'
        );

    resumeInput.addEventListener(
        'change',
        function () {
            const file = resumeInput.files[0];

            if (!file) {
                resumeFileName.textContent =
                    'Select one PDF file, maximum 5 MB.';

                return;
            }

            const extension = file.name
                .split('.')
                .pop()
                .toLowerCase();

            if (extension !== 'pdf') {
                window.alert(
                    'Resume must be a PDF file.'
                );

                resumeInput.value = '';

                resumeFileName.textContent =
                    'Select one PDF file, maximum 5 MB.';

                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                window.alert(
                    'Resume file size cannot exceed 5 MB.'
                );

                resumeInput.value = '';

                resumeFileName.textContent =
                    'Select one PDF file, maximum 5 MB.';

                return;
            }

            resumeFileName.textContent =
                'Selected: ' + file.name;
        }
    );

    const form =
        document.getElementById(
            'studentRegistrationForm'
        );

    const submitButton =
        document.getElementById(
            'submitStudentButton'
        );

    form.addEventListener(
        'submit',
        function (event) {
            if (!form.checkValidity()) {
                return;
            }

            const confirmed = window.confirm(
                'Register this student and create '
                + 'a new student login account?'
            );

            if (!confirmed) {
                event.preventDefault();

                return;
            }

            submitButton.disabled = true;

            submitButton.textContent =
                'Registering...';

            submitButton.classList.add(
                'opacity-60',
                'cursor-not-allowed'
            );
        }
    );
});
</script>