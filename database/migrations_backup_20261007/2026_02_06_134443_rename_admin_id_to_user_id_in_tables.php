<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Posts
        if (Schema::hasTable('posts')) {
            if (Schema::hasColumn('posts', 'admin_id')) {
                // Try to drop FK on admin_id
                try {
                    DB::statement('ALTER TABLE posts DROP FOREIGN KEY posts_admin_id_foreign');
                } catch (Exception $e) {
                }

                Schema::table('posts', function (Blueprint $table) {
                    if (Schema::hasColumn('posts', 'user_id')) {
                        $table->dropColumn('admin_id');
                    } else {
                        $table->renameColumn('admin_id', 'user_id');
                    }
                });
            }
        }

        // Products
        if (Schema::hasTable('products')) {
            if (Schema::hasColumn('products', 'admin_id')) {
                try {
                    DB::statement('ALTER TABLE products DROP FOREIGN KEY products_admin_id_foreign');
                } catch (Exception $e) {
                }

                Schema::table('products', function (Blueprint $table) {
                    if (Schema::hasColumn('products', 'user_id')) {
                        $table->dropColumn('admin_id');
                    } else {
                        $table->renameColumn('admin_id', 'user_id');
                    }
                });
            }
        } elseif (Schema::hasTable('product')) {
            if (Schema::hasColumn('product', 'admin_id')) {
                try {
                    DB::statement('ALTER TABLE product DROP FOREIGN KEY product_admin_id_foreign');
                } catch (Exception $e) {
                }

                Schema::table('product', function (Blueprint $table) {
                    if (Schema::hasColumn('product', 'user_id')) {
                        $table->dropColumn('admin_id');
                    } else {
                        $table->renameColumn('admin_id', 'user_id');
                    }
                });
            }
        }

        // Pages
        if (Schema::hasTable('pages')) {
            if (Schema::hasColumn('pages', 'admin_id')) {
                try {
                    DB::statement('ALTER TABLE pages DROP FOREIGN KEY pages_admin_id_foreign');
                } catch (Exception $e) {
                }

                Schema::table('pages', function (Blueprint $table) {
                    if (Schema::hasColumn('pages', 'user_id')) {
                        $table->dropColumn('admin_id');
                    } else {
                        $table->renameColumn('admin_id', 'user_id');
                    }
                });
            }
        } elseif (Schema::hasTable('page')) {
            if (Schema::hasColumn('page', 'admin_id')) {
                try {
                    DB::statement('ALTER TABLE page DROP FOREIGN KEY page_admin_id_foreign');
                } catch (Exception $e) {
                }

                Schema::table('page', function (Blueprint $table) {
                    if (Schema::hasColumn('page', 'user_id')) {
                        $table->dropColumn('admin_id');
                    } else {
                        $table->renameColumn('admin_id', 'user_id');
                    }
                });
            }
        }

        // Categories
        if (Schema::hasTable('categories')) {
            if (Schema::hasColumn('categories', 'admin_id')) {
                try {
                    DB::statement('ALTER TABLE categories DROP FOREIGN KEY categories_admin_id_foreign');
                } catch (Exception $e) {
                }

                Schema::table('categories', function (Blueprint $table) {
                    if (Schema::hasColumn('categories', 'user_id')) {
                        $table->dropColumn('admin_id');
                    } else {
                        $table->renameColumn('admin_id', 'user_id');
                    }
                });
            }
        } elseif (Schema::hasTable('category')) {
            if (Schema::hasColumn('category', 'admin_id')) {
                try {
                    DB::statement('ALTER TABLE category DROP FOREIGN KEY category_admin_id_foreign');
                } catch (Exception $e) {
                }

                Schema::table('category', function (Blueprint $table) {
                    if (Schema::hasColumn('category', 'user_id')) {
                        $table->dropColumn('admin_id');
                    } else {
                        $table->renameColumn('admin_id', 'user_id');
                    }
                });
            }
        }

        // Albums - Remove admin_id/user_id
        if (Schema::hasTable('albums')) {
            if (Schema::hasColumn('albums', 'admin_id')) {
                try {
                    DB::statement('ALTER TABLE albums DROP FOREIGN KEY albums_admin_id_foreign');
                } catch (Exception $e) {
                }
                Schema::table('albums', function (Blueprint $table) {
                    $table->dropColumn('admin_id');
                });
            }
            if (Schema::hasColumn('albums', 'user_id')) {
                try {
                    DB::statement('ALTER TABLE albums DROP FOREIGN KEY albums_user_id_foreign');
                } catch (Exception $e) {
                }
                Schema::table('albums', function (Blueprint $table) {
                    $table->dropColumn('user_id');
                });
            }
        }

        // Album Items - Remove admin_id/user_id
        if (Schema::hasTable('album_items')) {
            if (Schema::hasColumn('album_items', 'admin_id')) {
                try {
                    DB::statement('ALTER TABLE album_items DROP FOREIGN KEY album_items_admin_id_foreign');
                } catch (Exception $e) {
                }
                Schema::table('album_items', function (Blueprint $table) {
                    $table->dropColumn('admin_id');
                });
            }
            if (Schema::hasColumn('album_items', 'user_id')) {
                try {
                    DB::statement('ALTER TABLE album_items DROP FOREIGN KEY album_items_user_id_foreign');
                } catch (Exception $e) {
                }
                Schema::table('album_items', function (Blueprint $table) {
                    $table->dropColumn('user_id');
                });
            }
        }

        // AddToCardDetail
        if (Schema::hasTable('addtocard_detail')) {
            if (Schema::hasColumn('addtocard_detail', 'admin_id')) {
                try {
                    DB::statement('ALTER TABLE addtocard_detail DROP FOREIGN KEY addtocard_detail_admin_id_foreign');
                } catch (Exception $e) {
                }

                Schema::table('addtocard_detail', function (Blueprint $table) {
                    if (Schema::hasColumn('addtocard_detail', 'user_id')) {
                        $table->dropColumn('admin_id');
                    } else {
                        $table->renameColumn('admin_id', 'user_id');
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Simplified down for brevity
    }
};
