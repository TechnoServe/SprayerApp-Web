<?php

namespace sprint\traits;
// use \sprint\models\AuditLog;
// use \sprint\database\Model;

trait Auditable{

	public static function bootAuditable()
	{
		static::create(function(Model $model){
			self::audit("created", $model);
		});

		// static::updated(function(Model $model){
		// 	audit("updated", $model);
		// });

		// static::deleted(function(Model $model){
		// 	audit("deleted", $model);
		// });

		// static::restored(function(Model $model){
		// 	audit("restored", $model);
		// });
	}

	public function audit($description, $model)
	{

        // $model->removeEmptyEntries();
		echo "Auditing";
		var_dump($_SESSION["user"]["id"], $description, get_class($model), "<pre>", get_class_vars($model), "</pre>");
		// AuditLog::save([
		// 	"description" => $description,
		// 	"subject_id" => $model->id ?? null,
		// 	"subject_type" => get_class($model) ?? null,
		// 	"user_id" => 1,
		// 	"properties" => $model ?? null,
		// 	"created_at" => date("Y-m-d H:i:s")
		// ]);
	}
}