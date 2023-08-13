<!-- <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateEmployeesTable
 *
 * Migration to create the 'employees' database table.
 */
class CreateEmployeesTable extends Migration 
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {

            // Primary key
            $table->id(); 

            // Required fields
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            
            // Enum field with default 
            $table->enum('gender', ['Male', 'Female', 'Other'])->default('Other');

            // Unique field
            $table->string('email')->unique();
            
            // nullable fields
            $table->string('phone')->nullable();
            $table->string('emergency_contact_name')->nullable();;
            $table->string('emergency_contact_number')->nullable();;
            
            // Enum with default
            $table->enum('employee_status', ['active', 'on_leave', 'resigned', 'terminated'])->default('Active');
            
            // Other fields
            $table->string('position');
            $table->unsignedBigInteger('permission_id');
            $table->date('date_of_birth');
            $table->date('hire_date');
            $table->string('address_1')->nullable();
            $table->string('address_2')->nullable();
            $table->string('city')->nullable();;
            $table->string('postcode')->nullable();
            $table->string('country')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->timestamps();
            // Foreign keys 
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }

} -->