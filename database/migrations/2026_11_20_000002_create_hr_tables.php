<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color', 9)->default('#0DB5BC');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('designation');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('photo')->nullable();
            $table->text('bio')->nullable();
            $table->json('skills')->nullable();
            // permanent | remote_part_time | remote_full_time | contract | intern
            $table->string('employment_type')->default('permanent')->index();
            $table->string('work_mode')->default('onsite'); // onsite | remote | hybrid
            $table->decimal('monthly_salary', 12, 2)->nullable();   // permanent staff
            $table->decimal('hourly_rate', 10, 2)->nullable();      // remote part-time
            $table->unsignedSmallInteger('weekly_hours')->default(40);
            $table->date('join_date')->nullable();
            $table->string('status')->default('active')->index();   // active | on_leave | inactive
            $table->boolean('is_public')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('linkedin')->nullable();
            $table->string('github')->nullable();
            $table->string('facebook')->nullable();
            $table->timestamps();
        });

        Schema::create('work_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_member_id')->constrained()->cascadeOnDelete();
            $table->date('work_date');
            $table->decimal('hours', 5, 2);
            $table->string('description')->nullable();
            $table->string('status')->default('pending'); // pending | approved | rejected
            $table->timestamps();
            $table->index(['team_member_id', 'work_date']);
        });

        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_member_id')->constrained()->cascadeOnDelete();
            $table->string('type')->default('annual'); // annual | sick | casual | unpaid
            $table->date('start_date');
            $table->date('end_date');
            $table->string('reason')->nullable();
            $table->string('status')->default('pending'); // pending | approved | rejected
            $table->timestamps();
        });

        Schema::create('job_openings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('department')->nullable();
            $table->string('employment_type')->default('permanent');
            $table->string('location')->default('Remote');
            $table->text('description')->nullable();
            $table->text('requirements')->nullable();
            $table->boolean('is_open')->default(true);
            $table->timestamps();
        });

        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_opening_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->text('cover_letter')->nullable();
            $table->string('status')->default('new'); // new | shortlisted | interview | hired | rejected
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('job_openings');
        Schema::dropIfExists('leave_requests');
        Schema::dropIfExists('work_logs');
        Schema::dropIfExists('team_members');
        Schema::dropIfExists('departments');
    }
};
