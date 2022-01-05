<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Kinmuhyou Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Kinmuhyou newEmptyEntity()
 * @method \App\Model\Entity\Kinmuhyou newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Kinmuhyou[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Kinmuhyou get($primaryKey, $options = [])
 * @method \App\Model\Entity\Kinmuhyou findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Kinmuhyou patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Kinmuhyou[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Kinmuhyou|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Kinmuhyou saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Kinmuhyou[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Kinmuhyou[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Kinmuhyou[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Kinmuhyou[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class KinmuhyouTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('kinmuhyou');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);

        $this->hasOne('facesattendances', [
          'foreignKey' => 'syukin_time_id',
          'joinType' => 'LEFT',
          'Conditions' => ['facesattendances.inout_type' => '1']
        ]);

        $this->hasOne('facesattendances', [
          'foreignKey' => 'taikin_time_id',
          'joinType' => 'LEFT',
          'Conditions' => ['facesattendances.inout_type' => '2']
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->date('kinmu_date')
            ->requirePresence('kinmu_date', 'create')
            ->notEmptyDate('kinmu_date');

        $validator
            ->time('syukin_time')
            ->allowEmptyTime('syukin_time');

        $validator
            ->time('taikin_time')
            ->allowEmptyTime('taikin_time');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn('user_id', 'Users'), ['errorField' => 'user_id']);
        // $rules->add($rules->existsIn('syukin_time_id', 'SyukinTimes'), ['errorField' => 'syukin_time_id']);
        // $rules->add($rules->existsIn('taikin_time_id', 'TaikinTimes'), ['errorField' => 'taikin_time_id']);

        return $rules;
    }
}
