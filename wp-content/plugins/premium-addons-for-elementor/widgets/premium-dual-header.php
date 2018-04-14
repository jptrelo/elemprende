<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Premium_Dual_Header_Widget extends Widget_Base
{
    protected $templateInstance;

    public function getTemplateInstance(){
        return $this->templateInstance = premium_Template_Tags::getInstance();
    }
    
    public function get_name() {
        return 'premium-addon-dual-header';
    }

    public function get_title() {
        return esc_html__('Premium Dual Heading', 'premium-addons-for-elementor');
    }

    
    public function get_icon() {
        return 'pa-dual-header';
    }

    public function get_categories() {
        return [ 'premium-elements' ];
    }

    // Adding the controls fields for the premium dual header
    // This will controls the animation, colors and background, dimensions etc
    protected function _register_controls() {

        /*Start General Section*/
        $this->start_controls_section('premium_dual_header_general_settings',
                [
                    'label'         => esc_html__('Dual Heading', 'premium-addons-for-elementor')
                    ]
                );
        
        /*First Header*/
        $this->add_control('premium_dual_header_first_header_text',
                [
                    'label'         => esc_html__('First Heading', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'default'       => esc_html__('Premium', 'premium-addons-for-elementor'),
                    'label_block'   => true,
                    ]
                );
        
        /*Title Tag*/
        $this->add_control('premium_dual_header_first_header_tag',
                [
                    'label'         => esc_html__('HTML Tag', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'default'       => 'h2',
                    'options'       => [
                        'h1'    => 'H1',
                        'h2'    => 'H2',
                        'h3'    => 'H3',
                        'h4'    => 'H4',
                        'h5'    => 'H5',
                        'h6'    => 'H6',
                        ],
                    'label_block'   =>  true,
                    ]
                );
        
        /*Second Header*/
        $this->add_control('premium_dual_header_second_header_text',
                [
                    'label'         => esc_html__('Second Heading', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::TEXT,
                    'default'       => esc_html__('Addons', 'premium-addons-for-elementor'),
                    'label_block'   => true,
                    ]
                );
        
        /*Title Tag*/
        $this->add_control('premium_dual_header_second_header_tag',
                [
                    'label'         => esc_html__('HTML Tag', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'default'       => 'h2',
                    'options'       => [
                        'h1'    => 'H1',
                        'h2'    => 'H2',
                        'h3'    => 'H3',
                        'h4'    => 'H4',
                        'h5'    => 'H5',
                        'h6'    => 'H6',
                        ],
                    'label_block'   =>  true,
                    ]
                );
        
        /*Text Align*/
        $this->add_control('premium_dual_header_position',
                [
                    'label'         => esc_html__( 'Display', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => [
                        'inline'=> esc_html__('Inline', 'premium-addons-for-elementor'),
                        'block' => esc_html__('Block', 'premium-addons-for-elementor'),
                        ],
                    'default'       => 'inline',
                    'selectors'     => [
                        '{{WRAPPER}} .premium-dual-header-first-container, {{WRAPPER}} .premium-dual-header-second-container' => 'display: {{VALUE}};',
                        ],
                    'label_block'   => true
                    ]
                );
        
        $this->add_control('premium_dual_header_link_switcher',
                [
                    'label'         => esc_html__('Link', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SWITCHER,
                    'description'   => esc_html__('Enable or disable link','premium-addons-for-elementor'),
                    ]
                );
        
        $this->add_control('premium_dual_heading_link_selection', 
                [
                    'label'         => esc_html__('Link Type', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => [
                        'url'   => esc_html__('URL', 'premium-addons-for-elementor'),
                        'link'  => esc_html__('Existing Page', 'premium-addons-for-elementor'),
                    ],
                    'default'       => 'url',
                    'label_block'   => true,
                    'condition'     => [
                        'premium_dual_header_link_switcher'     => 'yes',
                        ]
                    ]
                );
        
        $this->add_control('premium_dual_heading_link',
                [
                    'label'         => esc_html__('Link', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::URL,
                    'default'       => [
                        'url'   => '#',
                        ],
                    'placeholder'   => 'https://premiumaddons.com/',
                    'label_block'   => true,
                    'separator'     => 'after',
                    'condition'     => [
                        'premium_dual_header_link_switcher'     => 'yes',
                        'premium_dual_heading_link_selection'   => 'url'
                        ]
                    ]
                );
        
        $this->add_control('premium_dual_heading_existing_link',
                [
                    'label'         => esc_html__('Existing Page', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'options'       => $this->getTemplateInstance()->get_all_post(),
                    'condition'     => [
                        'premium_dual_header_link_switcher'         => 'yes',
                        'premium_dual_heading_link_selection'       => 'link',
                    ],
                    'separator'     => 'after',
                    'label_block'   => true,
                    ]
                );
        
        /*Text Align*/
        $this->add_responsive_control('premium_dual_header_text_align',
                [
                    'label'         => esc_html__( 'Alignment', 'elementor' ),
                    'type'          => Controls_Manager::CHOOSE,
                    'options'       => [
                        'left'      => [
                            'title'=> esc_html__( 'Left', 'elementor' ),
                            'icon' => 'fa fa-align-left',
                            ],
                        'center'    => [
                            'title'=> esc_html__( 'Center', 'elementor' ),
                            'icon' => 'fa fa-align-center',
                            ],
                        'right'     => [
                            'title'=> esc_html__( 'Right', 'elementor' ),
                            'icon' => 'fa fa-align-right',
                            ],
                        ],
                    'default'       => 'center',
                    'selectors'     => [
                        '{{WRAPPER}} .premium-dual-header-container' => 'text-align: {{VALUE}};',
                        ],
                    ]
                );
        
        /*End General Settings Section*/
        $this->end_controls_section();
        
        /*Start First Header Styling Section*/
        $this->start_controls_section('premium_dual_header_first_style',
                [
                    'label'         => esc_html__('First Heading', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                ]
                );
        
        /*First Typography*/
        $this->add_group_control(
            Group_Control_Typography::get_type(),
                [
                    'name'          => 'first_header_typography',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-dual-header-first-header',
                    ]
                );
        
        /*First Coloring Style*/
        $this->add_control('premium_dual_header_first_back_clip',
                [
                    'label'         => esc_html__('Background Style', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'default'       => 'color',
                    'description'   => esc_html__('Choose ‘Normal’ style to put a background behind the text. Choose ‘Clipped’ style so the background will be clipped on the text.','premium-addons-for-elementor'),
                    'options'       => [
                        'color'         => esc_html__('Normal Background', 'premium-addons-for-elementor'),
                        'clipped'       => esc_html__('Clipped Background', 'premium-addons-for-elementor'),
                        ],
                    'label_block'   =>  true,
                    ]
                );
        
        /*First Color*/
        $this->add_control('premium_dual_header_first_color',
                [
                    'label'         => esc_html__('Text Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'condition'     => [
                      'premium_dual_header_first_back_clip' => 'color',
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-dual-header-first-header'   => 'color: {{VALUE}};',
                        ]
                    ]
                );
        
        /*First Background Color*/
        $this->add_group_control(
            Group_Control_Background::get_type(),
                [
                    'name'              => 'premium_dual_header_first_background',
                    'types'             => [ 'classic' , 'gradient' ],
                    'condition'         => [
                      'premium_dual_header_first_back_clip'  => 'color',
                    ],
                    'selector'          => '{{WRAPPER}} .premium-dual-header-first-header',
                    ]
                );
        
        /*First Clip*/
        $this->add_group_control(
            Group_Control_Background::get_type(),
                [
                    'name'              => 'premium_dual_header_first_clipped_background',
                    'types'             => [ 'classic' , 'gradient' ],
                    'condition'         => [
                      'premium_dual_header_first_back_clip'  => 'clipped',
                    ],
                    'selector'          => '{{WRAPPER}} .premium-dual-header-first-header',
                    ]
                );
        
        /*First Border*/
        $this->add_group_control(
            Group_Control_Border::get_type(),
                [
                    'name'              => 'first_header_border',
                    'selector'          => '{{WRAPPER}} .premium-dual-header-first-header',
                    ]
                );
        
        /*First Border Radius*/
        $this->add_control('premium_dual_header_first_border_radius',
                [
                    'label'         => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-dual-header-first-header' => 'border-radius: {{SIZE}}{{UNIT}};'
                        ]
                    ]
                );
        
        /*First Text Shadow*/
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label'             => esc_html__('Shadow','premium-addons-for-elementor'),
                'name'              => 'premium_dual_header_first_text_shadow',
                'selector'          => '{{WRAPPER}} .premium-dual-header-first-header',
            ]
            );
        
        /*First Margin*/
        $this->add_responsive_control('premium_dual_header_first_margin',
                [
                    'label'         => esc_html__('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-dual-header-first-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        /*First Padding*/
        $this->add_responsive_control('premium_dual_header_first_padding',
                [
                    'label'         => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-dual-header-first-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        /*End First Header Styling Section*/
        $this->end_controls_section();
        
        /*Start First Header Styling Section*/
        $this->start_controls_section('premium_dual_header_second_style',
                [
                    'label'         => esc_html__('Second Heading', 'premium-addons-for-elementor'),
                    'tab'           => Controls_Manager::TAB_STYLE,
                ]
                );
        
        /*Second Typography*/
        $this->add_group_control(
            Group_Control_Typography::get_type(),
                [
                    'name'          => 'second_header_typography',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-dual-header-second-header',
                    ]
                );
        
        /*Second Coloring Style*/
        $this->add_control('premium_dual_header_second_back_clip',
                [
                    'label'         => esc_html__('Background Style', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'default'       => 'color',
                    'description'   => esc_html__('Choose ‘Normal’ style to put a background behind the text. Choose ‘Clipped’ style so the background will be clipped on the text.','premium-addons-for-elementor'),
                    'options'       => [
                        'color'         => esc_html__('Normal Background', 'premium-addons-for-elementor'),
                        'clipped'       => esc_html__('Clipped Background', 'premium-addons-for-elementor'),
                        ],
                    'label_block'   =>  true,
                    ]
                );
        
        /*Second Color*/
        $this->add_control('premium_dual_header_second_color',
                [
                    'label'         => esc_html__('Text Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'condition'     => [
                      'premium_dual_header_second_back_clip' => 'color',
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-dual-header-second-header'   => 'color: {{VALUE}};',
                        ]
                    ]
                );
        
        /*Second Background Color*/
        $this->add_group_control(
            Group_Control_Background::get_type(),
                [
                    'name'              => 'premium_dual_header_second_background',
                    'types'             => [ 'classic' , 'gradient' ],
                    'condition'         => [
                      'premium_dual_header_second_back_clip'  => 'color',
                    ],
                    'selector'          => '{{WRAPPER}} .premium-dual-header-second-header',
                    ]
                );
        
        /*Second Clip*/
        $this->add_group_control(
            Group_Control_Background::get_type(),
                [
                    'name'              => 'premium_dual_header_second_clipped_background',
                    'types'             => [ 'classic' , 'gradient' ],
                    'condition'         => [
                      'premium_dual_header_second_back_clip'  => 'clipped',
                    ],
                    'selector'          => '{{WRAPPER}} .premium-dual-header-second-header',
                    ]
                );
        
        /*Second Border*/
        $this->add_group_control(
            Group_Control_Border::get_type(),
                [
                    'name'              => 'second_header_border',
                    'selector'          => '{{WRAPPER}} .premium-dual-header-second-header',
                ]
                );
        
        /*Second Border Radius*/
        $this->add_control('premium_dual_header_second_border_radius',
                [
                    'label'         => esc_html__('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-dual-header-second-header' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
                );
        
        /*Second Text Shadow*/
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label'             => esc_html__('Shadow','premium-addons-for-elementor'),
                'name'              => 'premium_dual_header_second_text_shadow',
                'selector'          => '{{WRAPPER}} .premium-dual-header-second-header',
            ]
            );
        
        /*Second Margin*/
        $this->add_responsive_control('premium_dual_header_second_margin',
                [
                    'label'         => esc_html__('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-dual-header-second-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        /*Second Padding*/
        $this->add_responsive_control('premium_dual_header_second_padding',
                [
                    'label'         => esc_html__('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-dual-header-second-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        /*End Second Header Styling Section*/
        $this->end_controls_section();
       
    }

    protected function render($instance = [])
    {
        // get our input from the widget settings.
        $settings = $this->get_settings();
        $this->add_inline_editing_attributes('premium_dual_header_first_header_text');
        $this->add_inline_editing_attributes('premium_dual_header_second_header_text');
        $first_title_tag = $settings['premium_dual_header_first_header_tag'];
        $second_title_tag = $settings['premium_dual_header_second_header_tag'];
        $first_title_text = $settings['premium_dual_header_first_header_text'] . ' ';
        $second_title_text = $settings['premium_dual_header_second_header_text'];
        $first_clip = '';
        $second_clip = '';
        if( $settings['premium_dual_header_first_back_clip'] === 'clipped' ) : $first_clip = "premium-dual-header-first-clip"; endif; 
        if( $settings['premium_dual_header_second_back_clip'] === 'clipped' ) : $second_clip = "premium-dual-header-second-clip"; endif; 
        
        $full_first_title_tag = '<' . $first_title_tag . ' class="premium-dual-header-first-header ' . $first_clip . '"><span '. $this->get_render_attribute_string('premium_dual_header_first_header_text') . '>' . $first_title_text . '</span></' . $settings['premium_dual_header_first_header_tag'] . '> ';
        
        $full_second_title_tag = '<' . $second_title_tag . ' class="premium-dual-header-second-header ' . $second_clip . '"><span '. $this->get_render_attribute_string('premium_dual_header_second_header_text'). '>' . $second_title_text . '</span></' . $settings['premium_dual_header_second_header_tag'] . '>';
        
        if( $settings['premium_dual_header_link_switcher'] =='yes' && $settings['premium_dual_heading_link_selection'] == 'link' ) {
            $link = get_permalink($settings['premium_dual_heading_existing_link']);
        } elseif( $settings['premium_dual_header_link_switcher'] =='yes' && $settings['premium_dual_heading_link_selection'] == 'url' ){
            $link = $settings['premium_dual_heading_link']['url'];
        }
?>
    
<div class="premium-dual-header-container">
    <?php if( $settings['premium_dual_header_link_switcher'] == 'yes' && ( !empty( $settings['premium_dual_heading_link']['url'] ) || !empty( $settings['premium_dual_heading_existing_link'] ) ) ) : ?>
    <a <?php if( !empty( $link ) ) : ?> href="<?php echo esc_attr($link); ?>" <?php endif; ?> <?php if(!empty($settings['premium_dual_heading_link']['is_external'])) : ?>target="_blank"<?php endif; ?><?php if(!empty($settings['premium_dual_heading_link']['nofollow'])) : ?>rel="nofollow"<?php endif; ?>>
        <?php endif; ?>
    <div class="premium-dual-header-first-container"><?php if ( !empty ( $settings['premium_dual_header_first_header_text'] ) ) : echo $full_first_title_tag; endif; ?></div>
    <div class="premium-dual-header-second-container"><?php if ( !empty ( $settings['premium_dual_header_second_header_text'] ) ) : echo $full_second_title_tag; endif; ?></div>
    <?php if( $settings['premium_dual_header_link_switcher'] == 'yes' && ( !empty( $settings['premium_dual_heading_link']['url'] ) || !empty( $settings['premium_dual_heading_existing_link'] ) ) ) : ?>
    </a>
    <?php endif; ?>
</div>

    <?php
    }
}
Plugin::instance()->widgets_manager->register_widget_type(new Premium_Dual_Header_Widget());
