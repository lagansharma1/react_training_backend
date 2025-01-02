<?php
class Courses
{
    /**
     *
     */
    public function __construct()
    {
    }

    /**
     *
     */
    public function __destruct()
    {
    }
    
    /**
     * Set friendly columns\' names to order tables\' entries
     */
    public function setOrderingValues()
    {
        $ordering = [
            'id' => 'ID',
            'course_name' => 'Course Name',
            'group_id' => 'Group',
            'created_at' => 'Created at'
        ];

        return $ordering;
    }
    public function get_videos($course_id, $db){
         $db->where('course_id', $course_id);
        $result = $db->get('course_videos');
        return count($result); // Return the count of rows
    }
}
?>
