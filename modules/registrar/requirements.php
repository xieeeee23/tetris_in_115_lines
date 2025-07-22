<?php
// Include your database connection here
// require_once '../../config/database.php';

// Sample student data - replace with your actual database query
// In real implementation, this would be something like:
// $students = $db->query("SELECT * FROM students ORDER BY last_name, first_name")->fetchAll();

$students = [
    ['id' => 1, 'first_name' => 'John', 'last_name' => 'Doe', 'grade_level' => 'Grade 11', 'section' => 'A', 'strand' => 'STEM', 'enrollment_status' => 'enrolled', 'requirements_complete' => 1],
    ['id' => 2, 'first_name' => 'Jane', 'last_name' => 'Smith', 'grade_level' => 'Grade 12', 'section' => 'B', 'strand' => 'ABM', 'enrollment_status' => 'pending', 'requirements_complete' => 0],
    ['id' => 3, 'first_name' => 'Mike', 'last_name' => 'Johnson', 'grade_level' => 'Grade 11', 'section' => 'C', 'strand' => 'HUMSS', 'enrollment_status' => 'enrolled', 'requirements_complete' => 1],
    ['id' => 4, 'first_name' => 'Sarah', 'last_name' => 'Wilson', 'grade_level' => 'Grade 12', 'section' => 'A', 'strand' => 'STEM', 'enrollment_status' => 'irregular', 'requirements_complete' => 0],
    ['id' => 5, 'first_name' => 'David', 'last_name' => 'Brown', 'grade_level' => 'Grade 11', 'section' => 'B', 'strand' => 'ABM', 'enrollment_status' => 'enrolled', 'requirements_complete' => 1],
    ['id' => 6, 'first_name' => 'Emily', 'last_name' => 'Davis', 'grade_level' => 'Grade 12', 'section' => 'C', 'strand' => 'HUMSS', 'enrollment_status' => 'pending', 'requirements_complete' => 0],
    ['id' => 7, 'first_name' => 'Alex', 'last_name' => 'Miller', 'grade_level' => 'Grade 11', 'section' => 'A', 'strand' => 'STEM', 'enrollment_status' => 'irregular', 'requirements_complete' => 1],
    ['id' => 8, 'first_name' => 'Maria', 'last_name' => 'Garcia', 'grade_level' => 'Grade 12', 'section' => 'B', 'strand' => 'GAS', 'enrollment_status' => 'enrolled', 'requirements_complete' => 1],
    ['id' => 9, 'first_name' => 'James', 'last_name' => 'Lee', 'grade_level' => 'Grade 11', 'section' => 'D', 'strand' => 'ICT', 'enrollment_status' => 'pending', 'requirements_complete' => 0],
    ['id' => 10, 'first_name' => 'Lisa', 'last_name' => 'Chen', 'grade_level' => 'Grade 12', 'section' => 'A', 'strand' => 'STEM', 'enrollment_status' => 'irregular', 'requirements_complete' => 0],
];

$relative_path = '../../'; // Adjust this based on your file structure

// Handle individual student requirements if student_id is provided
$selected_student = null;
if (isset($_GET['student_id'])) {
    $student_id = (int)$_GET['student_id'];
    foreach ($students as $student) {
        if ($student['id'] == $student_id) {
            $selected_student = $student;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Requirements Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .requirements-complete {
            border-left: 4px solid #28a745;
        }
        .requirements-incomplete {
            border-left: 4px solid #dc3545;
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        #noFiltersMessage {
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            color: #6c757d;
            padding: 3rem 2rem;
            text-align: center;
            border-radius: 8px;
        }
        .filter-section {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        .student-item {
            transition: all 0.3s ease;
        }
        .student-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="row">
            <!-- Student List Column -->
            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-white">
                            <i class="fas fa-users me-2"></i>Student List
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- Filter Section -->
                        <div class="filter-section">
                            <h6 class="mb-3">
                                <i class="fas fa-filter me-2"></i>Filter Students
                            </h6>
                            
                            <!-- Search box -->
                            <div class="mb-3">
                                <label for="studentSearch" class="form-label small">Search by Name:</label>
                                <input type="text" id="studentSearch" class="form-control form-control-sm" 
                                       placeholder="Enter student name...">
                            </div>
                            
                            <!-- Filter dropdowns -->
                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <label for="gradeFilter" class="form-label small">Grade:</label>
                                    <select id="gradeFilter" class="form-select form-select-sm">
                                        <option value="">All Grades</option>
                                        <option value="grade 11">Grade 11</option>
                                        <option value="grade 12">Grade 12</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="sectionFilter" class="form-label small">Section:</label>
                                    <select id="sectionFilter" class="form-select form-select-sm">
                                        <option value="">All Sections</option>
                                        <?php
                                        // Get unique sections
                                        $sections = array();
                                        foreach ($students as $student) {
                                            if (!in_array($student['section'], $sections) && !empty($student['section'])) {
                                                $sections[] = $student['section'];
                                            }
                                        }
                                        sort($sections);
                                        foreach ($sections as $section) {
                                            echo '<option value="' . htmlspecialchars($section) . '">' . htmlspecialchars($section) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <label for="strandFilter" class="form-label small">Strand:</label>
                                    <select id="strandFilter" class="form-select form-select-sm">
                                        <option value="">All Strands</option>
                                        <?php
                                        // Get unique strands
                                        $strands = array();
                                        foreach ($students as $student) {
                                            if (isset($student['strand']) && !empty($student['strand']) && !in_array($student['strand'], $strands)) {
                                                $strands[] = $student['strand'];
                                            }
                                        }
                                        sort($strands);
                                        foreach ($strands as $strand) {
                                            echo '<option value="' . htmlspecialchars($strand) . '">' . htmlspecialchars($strand) . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="statusFilter" class="form-label small">Status:</label>
                                    <select id="statusFilter" class="form-select form-select-sm">
                                        <option value="">All Statuses</option>
                                        <option value="enrolled">Enrolled</option>
                                        <option value="pending">Pending</option>
                                        <option value="irregular">Irregular</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row g-2 mb-3">
                                <div class="col-md-12">
                                    <label for="requirementsFilter" class="form-label small">Requirements:</label>
                                    <select id="requirementsFilter" class="form-select form-select-sm">
                                        <option value="">All Requirements</option>
                                        <option value="complete">Complete Requirements</option>
                                        <option value="incomplete">Incomplete Requirements</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Action buttons -->
                            <div class="d-flex justify-content-between align-items-center">
                                <button id="applyFilters" class="btn btn-primary btn-sm">
                                    <i class="fas fa-search me-1"></i> Apply Filters
                                </button>
                                <button id="resetFilters" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-undo me-1"></i> Reset
                                </button>
                            </div>
                        </div>
                        
                        <!-- Student counter -->
                        <div id="studentCounter" class="text-center mb-3 small text-muted">
                            Ready to search students
                        </div>
                        
                        <!-- No filters message (shown by default) -->
                        <div id="noFiltersMessage">
                            <i class="fas fa-users fa-3x mb-3 text-muted"></i>
                            <h5 class="text-muted">Please apply filters to view student data</h5>
                            <p class="text-muted small">Use the filters above and click "Apply Filters" to see students</p>
                        </div>
                        
                        <!-- Student list container (hidden by default) -->
                        <div class="list-group list-group-flush" id="studentList" style="max-height: 500px; overflow-y: auto; display: none;">
                            <?php foreach ($students as $student): ?>
                                <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?student_id=<?php echo $student['id']; ?>" 
                                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center student-item
                                         <?php echo ($student['requirements_complete'] == 1) ? 'requirements-complete' : 'requirements-incomplete'; ?>
                                         <?php echo (isset($_GET['student_id']) && $_GET['student_id'] == $student['id']) ? 'active' : ''; ?>"
                                   data-name="<?php echo htmlspecialchars(strtolower($student['first_name'] . ' ' . $student['last_name'])); ?>"
                                   data-grade="<?php echo htmlspecialchars(strtolower($student['grade_level'])); ?>"
                                   data-section="<?php echo htmlspecialchars($student['section']); ?>"
                                   data-strand="<?php echo htmlspecialchars($student['strand'] ?? ''); ?>"
                                   data-status="<?php echo htmlspecialchars(strtolower($student['enrollment_status'])); ?>"
                                   data-requirements="<?php echo $student['requirements_complete'] == 1 ? 'complete' : 'incomplete'; ?>">
                                    <div>
                                        <div class="fw-bold"><?php echo htmlspecialchars($student['last_name'] . ', ' . $student['first_name']); ?></div>
                                        <small class="text-muted">
                                            Grade: <?php echo htmlspecialchars($student['grade_level'] . ' - ' . $student['section']); ?>
                                            <?php if (isset($student['strand']) && !empty($student['strand'])): ?>
                                                | Strand: <?php echo htmlspecialchars($student['strand']); ?>
                                            <?php endif; ?>
                                            <br>
                                            <?php if ($student['requirements_complete'] == 1): ?>
                                                <span class="text-success"><i class="fas fa-check-circle"></i> Complete</span>
                                            <?php else: ?>
                                                <span class="text-danger"><i class="fas fa-times-circle"></i> Incomplete</span>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                    <span class="badge <?php 
                                        if ($student['enrollment_status'] === 'enrolled') echo 'bg-success';
                                        elseif ($student['enrollment_status'] === 'pending') echo 'bg-warning text-dark';
                                        else echo 'bg-info';
                                    ?> rounded-pill">
                                        <?php echo ucfirst(htmlspecialchars($student['enrollment_status'])); ?>
                                    </span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- No results message -->
                        <div id="noResultsMessage" style="display: none;">
                            <div class="text-center p-4">
                                <i class="fas fa-search fa-2x mb-3 text-muted"></i>
                                <h6 class="text-muted">No students found</h6>
                                <p class="text-muted small">Try adjusting your filters or search terms</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Student Requirements Column -->
            <div class="col-md-8">
                <?php if ($selected_student): ?>
                    <div class="card shadow">
                        <div class="card-header py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <h6 class="m-0 font-weight-bold text-white">
                                <i class="fas fa-clipboard-check me-2"></i>
                                Requirements for <?php echo htmlspecialchars($selected_student['first_name'] . ' ' . $selected_student['last_name']); ?>
                            </h6>
                        </div>
                        <div class="card-body">
                            <!-- Student Info -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5><?php echo htmlspecialchars($selected_student['last_name'] . ', ' . $selected_student['first_name']); ?></h5>
                                    <p class="text-muted mb-2">
                                        <strong>Grade:</strong> <?php echo htmlspecialchars($selected_student['grade_level']); ?><br>
                                        <strong>Section:</strong> <?php echo htmlspecialchars($selected_student['section']); ?><br>
                                        <?php if (!empty($selected_student['strand'])): ?>
                                            <strong>Strand:</strong> <?php echo htmlspecialchars($selected_student['strand']); ?><br>
                                        <?php endif; ?>
                                        <strong>Status:</strong> 
                                        <span class="badge <?php 
                                            if ($selected_student['enrollment_status'] === 'enrolled') echo 'bg-success';
                                            elseif ($selected_student['enrollment_status'] === 'pending') echo 'bg-warning text-dark';
                                            else echo 'bg-info';
                                        ?>">
                                            <?php echo ucfirst(htmlspecialchars($selected_student['enrollment_status'])); ?>
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6 text-end">
                                    <div class="alert <?php echo $selected_student['requirements_complete'] ? 'alert-success' : 'alert-warning'; ?> mb-0">
                                        <?php if ($selected_student['requirements_complete']): ?>
                                            <i class="fas fa-check-circle"></i> Requirements Complete
                                        <?php else: ?>
                                            <i class="fas fa-exclamation-triangle"></i> Requirements Incomplete
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Requirements List -->
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="mb-3">Required Documents:</h6>
                                    <div class="list-group">
                                        <?php
                                        // Sample requirements - replace with your actual requirements data
                                        $requirements = [
                                            ['name' => 'Form 138 (Report Card)', 'submitted' => true],
                                            ['name' => 'Birth Certificate', 'submitted' => $selected_student['requirements_complete']],
                                            ['name' => 'Good Moral Certificate', 'submitted' => true],
                                            ['name' => '2x2 ID Pictures', 'submitted' => $selected_student['requirements_complete']],
                                            ['name' => 'Medical Certificate', 'submitted' => false],
                                            ['name' => 'Parent/Guardian Information Sheet', 'submitted' => $selected_student['requirements_complete']],
                                        ];
                                        ?>
                                        <?php foreach ($requirements as $req): ?>
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <span><?php echo htmlspecialchars($req['name']); ?></span>
                                                <?php if ($req['submitted']): ?>
                                                    <span class="badge bg-success rounded-pill">
                                                        <i class="fas fa-check"></i> Submitted
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger rounded-pill">
                                                        <i class="fas fa-times"></i> Missing
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card shadow">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-user-graduate fa-4x text-muted mb-4"></i>
                            <h4 class="text-muted">Select a Student</h4>
                            <p class="text-muted">Choose a student from the list to view their requirements status</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let allStudents = [];
        let hasFiltersApplied = false;

        document.addEventListener('DOMContentLoaded', function() {
            // Store all student elements for filtering
            allStudents = Array.from(document.querySelectorAll('.student-item'));
            
            // Get DOM elements
            const applyFiltersBtn = document.getElementById('applyFilters');
            const resetFiltersBtn = document.getElementById('resetFilters');
            const studentSearch = document.getElementById('studentSearch');
            const gradeFilter = document.getElementById('gradeFilter');
            const sectionFilter = document.getElementById('sectionFilter');
            const strandFilter = document.getElementById('strandFilter');
            const statusFilter = document.getElementById('statusFilter');
            const requirementsFilter = document.getElementById('requirementsFilter');
            const studentList = document.getElementById('studentList');
            const noFiltersMessage = document.getElementById('noFiltersMessage');
            const noResultsMessage = document.getElementById('noResultsMessage');
            const studentCounter = document.getElementById('studentCounter');

            // Apply filters function
            function applyFilters() {
                const searchTerm = studentSearch.value.toLowerCase().trim();
                const gradeValue = gradeFilter.value.toLowerCase();
                const sectionValue = sectionFilter.value;
                const strandValue = strandFilter.value;
                const statusValue = statusFilter.value.toLowerCase();
                const requirementsValue = requirementsFilter.value;

                // Check if any filter is applied
                const hasAnyFilter = searchTerm || gradeValue || sectionValue || strandValue || statusValue || requirementsValue;
                
                if (!hasAnyFilter) {
                    // No filters applied, show message
                    studentList.style.display = 'none';
                    noFiltersMessage.style.display = 'block';
                    noResultsMessage.style.display = 'none';
                    studentCounter.textContent = 'Ready to search students';
                    hasFiltersApplied = false;
                    return;
                }

                hasFiltersApplied = true;
                noFiltersMessage.style.display = 'none';
                studentList.style.display = 'block';

                let visibleCount = 0;
                const totalStudents = allStudents.length;

                allStudents.forEach(student => {
                    let isVisible = true;

                    // Search filter (check both first name and last name)
                    if (searchTerm) {
                        const studentName = student.getAttribute('data-name');
                        if (!studentName.includes(searchTerm)) {
                            isVisible = false;
                        }
                    }

                    // Grade filter
                    if (gradeValue && isVisible) {
                        const studentGrade = student.getAttribute('data-grade');
                        if (studentGrade !== gradeValue) {
                            isVisible = false;
                        }
                    }

                    // Section filter
                    if (sectionValue && isVisible) {
                        const studentSection = student.getAttribute('data-section');
                        if (studentSection !== sectionValue) {
                            isVisible = false;
                        }
                    }

                    // Strand filter
                    if (strandValue && isVisible) {
                        const studentStrand = student.getAttribute('data-strand');
                        if (studentStrand !== strandValue) {
                            isVisible = false;
                        }
                    }

                    // Status filter
                    if (statusValue && isVisible) {
                        const studentStatus = student.getAttribute('data-status');
                        if (studentStatus !== statusValue) {
                            isVisible = false;
                        }
                    }

                    // Requirements filter
                    if (requirementsValue && isVisible) {
                        const studentRequirements = student.getAttribute('data-requirements');
                        if (studentRequirements !== requirementsValue) {
                            isVisible = false;
                        }
                    }

                    // Show/hide student
                    if (isVisible) {
                        student.style.display = 'block';
                        visibleCount++;
                    } else {
                        student.style.display = 'none';
                    }
                });

                // Update counter and show/hide no results message
                if (visibleCount === 0) {
                    studentList.style.display = 'none';
                    noResultsMessage.style.display = 'block';
                    studentCounter.textContent = 'No students found';
                } else {
                    noResultsMessage.style.display = 'none';
                    studentCounter.textContent = `Showing ${visibleCount} of ${totalStudents} students`;
                }
            }

            // Reset filters function
            function resetFilters() {
                studentSearch.value = '';
                gradeFilter.value = '';
                sectionFilter.value = '';
                strandFilter.value = '';
                statusFilter.value = '';
                requirementsFilter.value = '';
                
                // Hide student list and show message
                studentList.style.display = 'none';
                noFiltersMessage.style.display = 'block';
                noResultsMessage.style.display = 'none';
                studentCounter.textContent = 'Ready to search students';
                hasFiltersApplied = false;
            }

            // Event listeners
            applyFiltersBtn.addEventListener('click', applyFilters);
            resetFiltersBtn.addEventListener('click', resetFilters);

            // Allow Enter key to apply filters
            [studentSearch, gradeFilter, sectionFilter, strandFilter, statusFilter, requirementsFilter].forEach(element => {
                element.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        applyFilters();
                    }
                });
            });

            // Auto-apply filters if there's a selected student (page was loaded with student_id)
            <?php if ($selected_student): ?>
                // If a student is selected, show the list with all students visible
                studentList.style.display = 'block';
                noFiltersMessage.style.display = 'none';
                studentCounter.textContent = `Showing ${allStudents.length} of ${allStudents.length} students`;
                hasFiltersApplied = true;
            <?php endif; ?>
        });
    </script>
</body>
</html>