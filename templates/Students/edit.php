<?php
/**
 * templates/Students/edit.php
 */
$role = strtolower((string)($u['role'] ?? ''));
$studentId = (int)$student['id'];

$nameValue = (string)$this->getRequest()->getData('name', $student['name'] ?? '');
$selectedFaculty = (string)$this->getRequest()->getData('faculty', $student['faculty'] ?? '');
$selectedCourse = (string)$this->getRequest()->getData('course', $student['course'] ?? '');
$selectedSemester = (string)$this->getRequest()->getData('semester', $student['semester'] ?? '');
$phoneValue = (string)$this->getRequest()->getData('phone_number', $student['phone_number'] ?? '');
$addressValue = (string)$this->getRequest()->getData('address', $student['address'] ?? '');
$cityValue = (string)$this->getRequest()->getData('city', $student['city'] ?? '');
$postcodeValue = (string)$this->getRequest()->getData('postcode', $student['postcode'] ?? '');
$selectedState = (string)$this->getRequest()->getData('state', $student['state'] ?? '');

$faculties = [
    'Fakulti Sains Komputer',
    'Fakulti Pengurusan Perniagaan',
    'Fakulti Kejuruteraan',
];

$states = [
    'Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan',
    'Pahang', 'Perak', 'Perlis', 'Pulau Pinang', 'Sabah',
    'Sarawak', 'Selangor', 'Terengganu', 'Wilayah Persekutuan',
];

$backUrl = $role === 'student'
    ? ['controller' => 'Students', 'action' => 'view']
    : ['controller' => 'Students', 'action' => 'view', $studentId];
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-purple-500">
                <?= $role === 'student' ? 'Student / Profile' : 'Students / Edit' ?>
            </p>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white mt-1">
                Edit Student Profile
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                Matrix number is permanent and cannot be changed.
            </p>
        </div>

        <a href="<?= $this->Url->build($backUrl) ?>"
           class="inline-flex justify-center border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 font-bold px-4 py-2 rounded-xl">
            ← Back to Profile
        </a>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow overflow-hidden">
        <?= $this->Form->create(null, [
            'url' => ['controller' => 'Students', 'action' => 'edit', $studentId],
            'type' => 'post',
            'id' => 'studentEditForm',
        ]) ?>

        <div class="p-5 md:p-7 space-y-8">
            <section>
                <h2 class="font-black text-lg text-slate-900 dark:text-white">
                    Basic Information
                </h2>

                <div class="mt-5 grid md:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="block text-sm font-bold mb-2">Full Name *</label>
                        <input id="name" type="text" name="name"
                               value="<?= h($nameValue) ?>"
                               required maxlength="150"
                               class="w-full rounded-xl px-3 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700">
                    </div>

                    <div>
                        <label class="block text-sm font-bold mb-2">Matrix Number</label>
                        <input type="text"
                               value="<?= h((string)$student['matrix_number']) ?>"
                               readonly
                               class="w-full rounded-xl px-3 py-3 bg-slate-200 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-500 cursor-not-allowed">
                        <p class="text-xs text-slate-500 mt-1">
                            Permanent student identifier · exactly 12 digits.
                        </p>
                    </div>
                </div>
            </section>

            <section>
                <h2 class="font-black text-lg text-slate-900 dark:text-white">
                    Academic Information
                </h2>

                <div class="mt-5 grid md:grid-cols-3 gap-5">
                    <div>
                        <label for="faculty" class="block text-sm font-bold mb-2">Faculty *</label>
                        <select id="faculty" name="faculty" required
                                class="w-full rounded-xl px-3 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700">
                            <option value="">Select Faculty</option>
                            <?php foreach ($faculties as $faculty): ?>
                                <option value="<?= h($faculty) ?>"
                                    <?= $selectedFaculty === $faculty ? 'selected' : '' ?>>
                                    <?= h($faculty) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label for="course" class="block text-sm font-bold mb-2">Course *</label>
                        <select id="course" name="course" required
                                data-selected-course="<?= h($selectedCourse) ?>"
                                class="w-full rounded-xl px-3 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700">
                            <option value="">Select Faculty First</option>
                        </select>
                    </div>

                    <div>
                        <label for="semester" class="block text-sm font-bold mb-2">Semester *</label>
                        <select id="semester" name="semester" required
                                class="w-full rounded-xl px-3 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700">
                            <option value="">Select Semester</option>
                            <?php for ($semester = 1; $semester <= 8; $semester++): ?>
                                <option value="<?= $semester ?>"
                                    <?= $selectedSemester === (string)$semester ? 'selected' : '' ?>>
                                    Semester <?= $semester ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
            </section>

            <section>
                <h2 class="font-black text-lg text-slate-900 dark:text-white">
                    Contact Information
                </h2>

                <div class="mt-5 grid md:grid-cols-2 gap-5">
                    <div>
                        <label for="phone_number" class="block text-sm font-bold mb-2">Phone Number *</label>
                        <input id="phone_number" type="text" name="phone_number"
                               value="<?= h($phoneValue) ?>"
                               required maxlength="20"
                               class="w-full rounded-xl px-3 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700">
                    </div>

                    <div>
                        <label for="state" class="block text-sm font-bold mb-2">State</label>
                        <select id="state" name="state"
                                class="w-full rounded-xl px-3 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700">
                            <option value="">Select State</option>
                            <?php foreach ($states as $state): ?>
                                <option value="<?= h($state) ?>"
                                    <?= $selectedState === $state ? 'selected' : '' ?>>
                                    <?= h($state) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-bold mb-2">Street Address</label>
                        <input id="address" type="text" name="address"
                               value="<?= h($addressValue) ?>"
                               maxlength="255"
                               class="w-full rounded-xl px-3 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700">
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-bold mb-2">City</label>
                        <input id="city" type="text" name="city"
                               value="<?= h($cityValue) ?>"
                               maxlength="100"
                               class="w-full rounded-xl px-3 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700">
                    </div>

                    <div>
                        <label for="postcode" class="block text-sm font-bold mb-2">Postcode</label>
                        <input id="postcode" type="text" name="postcode"
                               value="<?= h($postcodeValue) ?>"
                               maxlength="10"
                               class="w-full rounded-xl px-3 py-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700">
                    </div>
                </div>
            </section>
        </div>

        <div class="px-5 md:px-7 py-5 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-700 flex justify-end gap-3">
            <a href="<?= $this->Url->build($backUrl) ?>"
               class="inline-flex justify-center border border-slate-300 dark:border-slate-700 px-5 py-3 rounded-xl font-bold">
                Cancel
            </a>
            <button id="saveStudentButton" type="submit"
                    class="inline-flex justify-center bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-xl font-bold">
                Save Changes
            </button>
        </div>

        <?= $this->Form->end() ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const courses = {
        'Fakulti Sains Komputer': [
            'Sistem Maklumat', 'Sains Komputer', 'Teknologi Maklumat', 'Multimedia'
        ],
        'Fakulti Pengurusan Perniagaan': [
            'Pengurusan Perniagaan', 'Pemasaran', 'Kewangan', 'Pengurusan Sumber Manusia'
        ],
        'Fakulti Kejuruteraan': [
            'Kejuruteraan Awam', 'Kejuruteraan Elektrik', 'Kejuruteraan Mekanikal', 'Kejuruteraan Kimia'
        ]
    };

    const faculty = document.getElementById('faculty');
    const course = document.getElementById('course');

    function loadCourses() {
        const selected = course.dataset.selectedCourse || '';
        course.innerHTML = '<option value="">Select Course</option>';

        (courses[faculty.value] || []).forEach(function (value) {
            const option = document.createElement('option');
            option.value = value;
            option.textContent = value;
            option.selected = value === selected;
            course.appendChild(option);
        });
    }

    faculty.addEventListener('change', function () {
        course.dataset.selectedCourse = '';
        loadCourses();
    });

    loadCourses();

    const form = document.getElementById('studentEditForm');
    const button = document.getElementById('saveStudentButton');

    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
            return;
        }

        if (!window.confirm('Save changes to this profile?')) {
            event.preventDefault();
            return;
        }

        button.disabled = true;
        button.textContent = 'Saving...';
    });
});
</script>
