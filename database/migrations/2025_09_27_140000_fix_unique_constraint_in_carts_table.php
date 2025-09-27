<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check for foreign keys on carts table
        $foreignKeys = $this->getForeignKeys('carts');
        
        // Drop foreign key first if it exists
        if (in_array('carts_user_id_foreign', $foreignKeys)) {
            Schema::table('carts', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        }

        // Try to drop the unique constraint if it exists
        try {
            DB::statement('ALTER TABLE carts DROP INDEX carts_user_id_unique');
        } catch (\Exception $e) {
            // Index doesn't exist, that's fine
        }

        // Re-add the foreign key constraint
        Schema::table('carts', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check for foreign keys on carts table
        $foreignKeys = $this->getForeignKeys('carts');
        
        // Drop foreign key first if it exists
        if (in_array('carts_user_id_foreign', $foreignKeys)) {
            Schema::table('carts', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        }
        
        // Try to add the unique constraint if it doesn't exist
        try {
            // Check if the index exists first
            $indexExists = DB::select("
                SELECT COUNT(*) as count FROM INFORMATION_SCHEMA.STATISTICS 
                WHERE table_schema = DATABASE() 
                AND table_name = 'carts' 
                AND index_name = 'carts_user_id_unique'
            ")[0]->count > 0;
            
            if (!$indexExists) {
                DB::statement('ALTER TABLE carts ADD UNIQUE INDEX carts_user_id_unique(user_id)');
            }
        } catch (\Exception $e) {
            // Can't add index, that's fine
        }
        
        // Re-add the foreign key constraint
        Schema::table('carts', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });
    }
    
    /**
     * Get all foreign keys for a given table
     */
    private function getForeignKeys($tableName)
    {
        $foreignKeys = [];
        
        try {
            // Get foreign key constraint names
            $constraints = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = '{$tableName}' 
                AND CONSTRAINT_TYPE = 'FOREIGN KEY'
            ");
            
            foreach ($constraints as $constraint) {
                $foreignKeys[] = $constraint->CONSTRAINT_NAME;
            }
        } catch (\Exception $e) {
            // Error getting foreign keys
        }
        
        return $foreignKeys;
    }
};